const alunos = [
    {
        id: 1,
        nome: "Maria Clara",
        email: "maria@hotmail.com",
        curso: "Engenharia Computação",
        semestre: 9,

        metrics: {
            risk_score: 40,
            autonomy_score: 4,
            general_readiness_score: 70
        },

        respostas: [
            {
                question_id: 1,
                pergunta: "Qual campo deseja estudar?",
                resposta: "Matemática"
            },
            {
                question_id: 2,
                pergunta: "Tema de interesse",
                resposta: "Cálculo"
            }
        ]
    },

    {
        id: 2,
        nome: "Ravi Luan",
        email: "ravi@hotmail.com",
        curso: "Design",
        semestre: 4,

        metrics: {
            risk_score: 20,
            autonomy_score: 0,
            general_readiness_score: 70
        },

        respostas: [
            {
                question_id: 1,
                pergunta: "Qual campo deseja estudar?",
                resposta: "História"
            },

            {
                question_id: 2,
                pergunta: "Tema de interesse",
                resposta: "Artes"
            }
        ]
    }
]

export function getMockAlunos() {
    return Promise.resolve(alunos)
}

export function getMockAlunoById(id) {
    const aluno = alunos.find(a => a.id == id)
    return Promise.resolve(aluno)
}

export function getMockDashboard() {
    const total = alunos.length

    const mediaAutonomia =
        alunos.reduce((acc, a) => acc + a.metrics.autonomy_score, 0) / total

    return Promise.resolve({
        total_alunos: total,
        media_autonomia: mediaAutonomia.toFixed(1)
    })
}