# Care Business

<h3>
	A PHP test for Care Business Interview 
</h3>
<p>
  <a href="https://www.linkedin.com/in/fabianoone/">
    <img alt="Fabiano Oliveira" title="Fabiano Oliveira | Dev" src="https://avatars.githubusercontent.com/u/3976796?s=48&v=4" width="19">
    <img alt="Made by Fabiano Oliveira" src="https://img.shields.io/badge/made%20by-fabianoone-%234c1">
  </a>
</p>


O teste tem como intuíto gerenciar as notas fiscais do cliente com os devidos TODOs:

- ✔ O sistema deve ter uma tela para realizar upload de um arquivo na extensão ".xml"
- ✔ O sistema deve validar se o arquivo é uma extensão .xml
- ✔ O sistema deve permitir somente o upload do arquivo xml se o campo CNPJ do emitente(<emit>) for "09066241000884"
- ✔ O sistema deve validar se a nota possui protocolo de autorização preenchido (campo <nProt>)
  
  
- O sistema deve exibir em uma tela os seguintes dados:
  - ✔ Número da nota Fiscal
  - ✔ Data da nota Fiscal
  - ✔ Dados completos do destinatário e valor total da nota fiscal

- Requisitos não funcionais:
  - ✔ Os dados que serão exibidos na tela
  - ✔ deverão ser armazenados em um banco de dados MySQL
  - ✔ Deverá ser desenvolvido em linguagem PHP 7

  
### Como rodar o projeto:
  1. Clone ou baixe o projeto para ambiente local.
  2. na pasta do projeto descompactado rode o PHP Server com o seguinte comando no Terminal CMD:  `php -S localhost:8181`
  3. Importe as tabelas ([do arquivo](https://github.com/fabianoone/carebusiness/blob/main/cb_vaga_php_db.zip) ) para o banco de dados e configure o arquivo de banco de dados (https://github.com/fabianoone/carebusiness/blob/main/inc/db.php)
  4. Acesse `http://localhost:8181` para conferir o projeto rodando.
  
  
  
### :rocket: Tecnologias

##### Para o teste foram utilizadas as seguintes:

- [Bootstrap](https://getbootstrap.com/)
- [jQuery](https://jquery.com/)
- [PHP 7](https://www.php.net/)
- [MySQL](https://www.mysql.com/)

  
  
### Resultado esperado: 

##### Tela front para upload do arquivo XML
![Tela front para upload do arquivo XML](https://github.com/fabianoone/carebusiness/blob/main/front.jpeg)


##### Tela de visualização da nota fiscal cadastrada no banco de dados  
![Tela de visualização da nota fiscal cadastrada no banco de dados](https://github.com/fabianoone/carebusiness/blob/main/frontNotaCadatrada.jpeg) 

