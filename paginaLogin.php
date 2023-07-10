<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="https://localhost/meusOutrosArquivos/estilizacaoLogin.css">
    <title>Página de login</title>
</head>
<body>

    <?php

        //conectar com o mysql
        //dadosMysql[0] = servidor; dadosMysql[1] = nome do usuario; dadosMysql[2] = senha do mysql
        $dadosMysql = array("localhost", "root", "abcd");
        $conexao = new mysqli($dadosMysql[0], $dadosMysql[1], $dadosMysql[2]);

        #buscar valores no banco de dados e retornar array com os dados encontrados
        function buscarUsuarios(){
            global $conexao;
            $comandoSql = "SELECT nome, senha FROM telalogin.usuarios";
            $valoresEncontrados = $conexao->query($comandoSql);
            $dadosUsuarios = [];
            while($colunasValores = $valoresEncontrados->fetch_assoc()) {
                $dadosUsuarios[] = $colunasValores;
            };
            return $dadosUsuarios;}

        $valoresEncontrados = buscarUsuarios();

        //retornar se o login foi inserido corretamente
        function validarLogin($usuario, $senha) {
            //re-utilizar o array com os usuarios criado anteriormente
            global $valoresEncontrados;
            //variável que definirá se ambos o nome de usuário e senha estão no mesmo endereço no banco de dados
            $estadoVerificacao = "usuarioInexistente";
            #checagem que definirá se a variável acima terá valor true ou false
            for($x = 0; $x < sizeof($valoresEncontrados); ++$x){
                if($usuario == $valoresEncontrados[$x]["nome"]){
                    if($senha == $valoresEncontrados[$x]["senha"]){ $estadoVerificacao = "sucesso"; }
                    else{ $estadoVerificacao = "erroSenha"; }
                    break;
                }} 
            return $estadoVerificacao;
        }
        //executar código abaixo quando "botaoLogin" for pressionado
        if(isset($_POST['botaoLogin'])) {
            //receber textos inseridos dentro dos campos de usuário e senha
            $usuarioInserido = $_POST['campoInserirUsuario'];
            $senhaInserida = $_POST["campoInserirSenha"];
            //salvar resultado da funcão "validarLogin" dentro da variável, se a variável tiver valor false ou "erroSenha", mostrar mensagem de erro correspondente, senão, mostrar mensagem confirmando que o login foi feito com sucesso.
            $resultadoVerificacao = validarLogin($usuarioInserido, $senhaInserida);
            if($resultadoVerificacao == "usuarioInexistente"){echo "<p style='font-size:20px; color:red; position:absolute; top:185px; left:840px;'>Usuário não encontrado!</p>";}            
            else if($resultadoVerificacao == "erroSenha"){ echo "<p style='font-size:20px; color:red; position:absolute; top:285px; left:840px;'>Senha incorreta.</p>"; }
            else{ echo "<p style='font-size:20px; color:rgb(0,250,0); position:absolute; top:400px; left:750px;'>Login feito com sucesso!</p>"; }
        }
        //redirecionar para página de criar conta se o "botaoNovaConta" for pressionado.
        else if(isset($_GET['botaoNovaConta'])){
            header("Location: https://localhost/paginaNovaConta.php");
            exit();
        }
    ?>

    <img id="logo" src="https://localhost/meusOutrosArquivos/imagens/logo.png">
    <form method="post">
        <input class="camposInserirDados" id="campoUsuario" type="text" name="campoInserirUsuario">
        <input class="camposInserirDados" id="campoSenha" type="password" name="campoInserirSenha">
        <input type="submit" id="botaoFazerLogin" name="botaoLogin" value = "Fazer Login">
    </form>
    <form method="get">
        <input type="submit" id="botaoCriarConta" name="botaoNovaConta" value = "Criar uma conta">
    </form>
    <p class="textosCampos" id="textoUsuário">Usuário:</p>
    <p class="textosCampos" id="textoSenha">Senha:</p>
</body>
</html>