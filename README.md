para começar e necessario popular o DB, comecando pela tabela user depois carro, fipe, veiculo.

as requisiçoes POST, PUT, DELETE sempre tem que ser enviado um JSON com pelo menos { "user_id": "role"} para a "autenticação", e post e put so colocar os outos campos.

as rotas são (
/user
/carro
/fipe
/veiculo
)
carro, fipe e veiculo tem metodo GET  /rota/{id}.

clone depois pode fazer build docker e up.
