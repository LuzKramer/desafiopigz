api em php Symfony framework dockerizado. so fazer o clone rodar ( docker compose build --no-cache ) o container e ( docker composer up -d ) e tudo deve funcionar !

para começar e necessario popular o DB, comecando pela tabela user depois carro, fipe, veiculo.

as requisiçoes POST, PUT, DELETE sempre tem que ser enviado um JSON com pelo menos { "user_id": "role"} para a autenticação se o user e admin.  post e put só colocar os outos campos.

as rotas são (
/user
/carro
/fipe
/veiculo
)
carro, fipe e veiculo tem metodo GET  /rota/{id}.

clone depois pode fazer build docker e up.
usando docker compose build --no-cache o container deve funcionar direito.

json dos POSTS{


  USER
{
  "name":"adm",
  "role":"admin"
}
  CARRO
 {
  "user_id": 1,
  "valor": 75000,
  "modelo":"fusion",
  "fabricante": "Ford",
  "ano": 2010,
  "version": "2.5LX",
  "combustivel":"gasolina"
  
}
  FIPE
  {
  "user_id": 1,
  "carro_id":1,
  "valor": 40000
  }

  VEICULO
  {
  "user_id": 1,
  "carro_id":1,
  "valor": 40000,
  "cor":"azul",
  "kilometragem":150000
  }


}
