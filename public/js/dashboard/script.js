import { getAlunos, getAlunoById } from '../services/alunosService.js'
import { getDashboard } from '../services/dashboardService.js'

const tabela = document.getElementById('tabela-alunos')
const filtroCurso = document.getElementById('filter-curso')
const filtroSemestre = document.getElementById('filter-semestre')
const searchInput = document.getElementById('searchAluno')
const clearBtn = document.getElementById('clear-filters')

let alunos = []
let alunosFiltrados = []

let chartEngajamento = null
let chartRisco = null

async function init() {
    try {
        alunos = await getAlunos()
        preencherFiltros()
        aplicarFiltros()
        await loadDashboard()
    } catch (error) {
        console.error('Erro ao carregar dados do admin:', error)
        tabela.innerHTML = `<tr><td colspan="5" class="text-center">Nao foi possivel carregar os dados reais.</td></tr>`
        if (window.toastr) {
            window.toastr.error('Nao foi possivel carregar os dados do painel administrativo.')
        }
    }
}

init()

async function loadDashboard() {
    const data = await getDashboard()

    document.getElementById("total-alunos").textContent = data.total_alunos
    document.getElementById("media-engajamento").textContent = data.media_autonomia
}

function renderTabela(lista) {
    tabela.innerHTML = ''

    if (lista.length === 0) {
        tabela.innerHTML = `<tr><td colspan="5" class="text-center">Nenhum aluno encontrado</td></tr>`
        return
    }

    lista.forEach(aluno => {
        tabela.innerHTML += `
            <tr>
                <td>${aluno.nome}</td>
                <td>${aluno.curso}</td>
                <td>${aluno.semestre}</td>
                <td>${aluno.metrics.autonomy_score}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="verAluno(${aluno.id})">
                        Ver
                    </button>
                </td>
            </tr>
        `
    })
}

function preencherFiltros() {
    const cursos = [...new Set(alunos.map(a => a.curso))]

    cursos.forEach(curso => {
        filtroCurso.innerHTML += `<option value="${curso}">${curso}</option>`
    })
}

function aplicarFiltros() {
    let resultado = [...alunos]

    const curso = filtroCurso.value
    const semestre = filtroSemestre.value
    const busca = searchInput.value.toLowerCase()

    if (curso) {
        resultado = resultado.filter(a => a.curso === curso)
    }

    if (semestre) {
        resultado = resultado.filter(a => a.semestre == semestre)
    }

    if (busca) {
        resultado = resultado.filter(a =>
            a.nome.toLowerCase().includes(busca)
        )
    }

    alunosFiltrados = resultado

    renderTabela(resultado)
    atualizarGraficos(resultado)
}

function atualizarGraficos(lista) {
    criarGraficoEngajamento(lista)
    criarGraficoRisco(lista)
}

function criarGraficoEngajamento(lista) {
    const ctx = document.getElementById('graficoEngajamento')

    if (chartEngajamento) chartEngajamento.destroy()

    chartEngajamento = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: lista.map(a => a.nome),
            datasets: [{
                label: 'Autonomia',
                data: lista.map(a => a.metrics.autonomy_score)
            }]
        }
    })
}

function criarGraficoRisco(lista) {
    const ctx = document.getElementById('graficoRisco')

    if (chartRisco) chartRisco.destroy()

    let baixo = 0, medio = 0, alto = 0

    lista.forEach(a => {
        const r = a.metrics.risk_score

        if (r < 40) baixo++
        else if (r < 70) medio++
        else alto++
    })

    chartRisco = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Baixo', 'Médio', 'Alto'],
            datasets: [{
                data: [baixo, medio, alto]
            }]
        }
    })
}

filtroCurso.addEventListener('change', aplicarFiltros)
filtroSemestre.addEventListener('change', aplicarFiltros)
searchInput.addEventListener('input', aplicarFiltros)

clearBtn.addEventListener('click', () => {
    filtroCurso.value = ''
    filtroSemestre.value = ''
    searchInput.value = ''
    aplicarFiltros()
})

window.verAluno = async function (id) {
    try {
        const aluno = await getAlunoById(id)

        document.getElementById("detalhe-aluno").innerHTML = `
            <h5>${aluno.nome}</h5>
            <p><strong>Curso:</strong> ${aluno.curso}</p>
            <p><strong>Semestre:</strong> ${aluno.semestre}</p>

            <hr>

            <h6>Respostas:</h6>
            ${aluno.respostas.map(r => `
                <p><strong>${r.pergunta}:</strong> ${r.resposta}</p>
            `).join('')}
        `

        const modal = new bootstrap.Modal(document.getElementById('modalAluno'))
        modal.show()
    } catch (error) {
        console.error('Erro ao carregar aluno:', error)
        if (window.toastr) {
            window.toastr.error('Nao foi possivel carregar os detalhes do aluno.')
        }
    }
}

let chartExpandido = null

window.abrirGrafico = function(tipo) {
    const ctx = document.getElementById('graficoExpandido')

    if (chartExpandido) chartExpandido.destroy()

    if (tipo === 'engajamento') {
        chartExpandido = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: alunosFiltrados.map(a => a.nome),
                datasets: [{
                    label: 'Autonomia',
                    data: alunosFiltrados.map(a => a.metrics.autonomy_score)
                }]
            }
        })

        document.getElementById("tituloGrafico").textContent = "Engajamento dos Alunos"
    }

    if (tipo === 'risco') {
        let baixo = 0, medio = 0, alto = 0

        alunosFiltrados.forEach(a => {
            const r = a.metrics.risk_score
            if (r < 40) baixo++
            else if (r < 70) medio++
            else alto++
        })

        chartExpandido = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Baixo', 'Médio', 'Alto'],
                datasets: [{
                    data: [baixo, medio, alto]
                }]
            }
        })

        document.getElementById("tituloGrafico").textContent = "Distribuição de Risco"
    }

    const modal = new bootstrap.Modal(document.getElementById('modalGrafico'))
    modal.show()
}
