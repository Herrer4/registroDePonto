# registroDePonto
O sistema foi elaborado em PHP com Framework Laravel 8 e Vuejs v2, sendo assim, são necessários alguns pré-requisitos para que o sistema funcione, são eles: PHP ≥ 8
Composer (https://getcomposer.org/)
Recomendo instalar o XAMPP, pois já vem com o PHP, MySQL, APACHE, utilizaremos eles como banco de dados e servidor de aplicação, sendo necessário instalar o composer por fora.
1 – Após a extração dos arquivos, vá até a pasta raiz do sistema e digite o comando “composer install”
2 – Agora com o xampp funcionando é necessário criar o banco de dados.
Cique em “Admin” do MySQL, deverá abrir o http://localhost/phpmyadmin/
Clique em “novo” (para criar um novo banco de dados) no canto superior esquerdo.
Em seguida informe o nome do banco de deseja criar e clique em “Criar”.
3 – Agora tem que configurar o arquivo .env que fica na raiz do projeto.
Nesse arquivo iremos passar as configurações do banco. 
DB_DATABASE=nome_de_sua_database
DB_USERNAME=root
DB_PASSWORD=
Por padrão o MySQL tem o usuário root e sem senha.
4 – Agora temos que migrar as tabelas para o banco com o comando “php artisan migrate --seed”
obs: Se precisar resetar o banco de dados para fins de teste ou qualquer outra coisa insira o comando a seguir: php artisan migrate:fresh –seed
5 – Agora para iniciar o sistema digite “php artisan serve”
usuario: admin@admin.com senha: 123456
