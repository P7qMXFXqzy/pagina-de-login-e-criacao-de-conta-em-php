<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="https://localhost/meusOutrosArquivos/estilizacaoNovaConta.css">
    <title>Página de criar conta</title>
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
        
        //retornar se o usuário já existe no banco de dados
        function checarSeUsuarioExiste($usuarioInserido) {
            //receber texto inserido dentro do campo de usuário
            //re-utilizar o array com os usuarios criado anteriormente
            global $valoresEncontrados;
            //variável que definirá se ambos o nome de usuário e senha estão no mesmo endereço no banco de dados
            $usuarioEncontrado = false;
            #checagem que definirá se a variável acima terá valor true ou false
            for($x = 0; $x < sizeof($valoresEncontrados); ++$x){
                if($usuarioInserido == $valoresEncontrados[$x]["nome"]){
                    $usuarioEncontrado = true;
                    break;}
            } 
            return $usuarioEncontrado;
        }
        
        //retornar se a senha é maior que 10 digitos
        function checarTamanhoSenha($senhaInserida){
            //variável que definirá se ambos o nome de usuário e senha estão no mesmo endereço no banco de dados
            $tamanhoSenha = strlen(($senhaInserida));
            #retornar true se a senha tem 10 ou mais caracteres, retornar false se não.
            if($tamanhoSenha >= 10){return true;}
            else{return false;}
        }

        //salvar novo usuário no banco de dados
        function salvarNovoUsuario($nome, $senha){
            global $conexao;
            $comandoSql = "INSERT INTO telalogin.usuarios VALUES ('".$nome."', '".$senha."')";
            mysqli_query($conexao, $comandoSql);
        }

        //executar código abaixo quando "botaoCriarNovaConta" for pressionado
        if(isset($_POST['botaoCriarNovaConta'])) {
            $usuarioInserido = $_POST["campoInserirUsuario"];
            $senhaInserida = $_POST["campoInserirSenha"]; 
            $senhaConfirmativa = $_POST["campoSenhaConfirmar"];
            $checagemUsuario = checarSeUsuarioExiste($usuarioInserido);
            $checagemTamanhoSenha = checarTamanhoSenha($senhaInserida);
            //se todas as condições estiverem corretas, salvar nova conta no banco de dados e mostrar mensagem indicando que a conta foi salva
            if($usuarioInserido != "" AND $checagemUsuario == false AND $senhaInserida == $senhaConfirmativa AND $checagemTamanhoSenha == true){
                salvarNovoUsuario($usuarioInserido, $senhaInserida);
                echo "<p style='font-size:20px; color:rgb(0,250,0); position:absolute; top:400px; left:750px;'>Conta criada com sucesso!</p>";
                global $valoresEncontrados;
                $valoresEncontrados = buscarUsuarios();
            }
            else{
                //mostrar mensagem de erro pra cada erro cometido pelo usuário
                if($checagemUsuario == true){ echo "<p style='font-size:20px; color:red; position:absolute; top:85px; left:840px;'>Usuário já existente</p>"; }
                else if($usuarioInserido == ""){ echo"<p style='font-size:20px; color:red; position:absolute; top:85px; left:840px;'>Usuário não inserido!</p>"; }
                if($senhaInserida != $senhaConfirmativa){ echo "<p style='font-size:20px; color:red; position:absolute; top:285px; left:840px;'>Insira a mesma senha acima!</p>"; }
                if($checagemTamanhoSenha == false){ echo "<p style='font-size:20px; color:red; position:absolute; top:185px; left:840px;'>Sua senha tem menos de 10 dígitos.</p>"; }
            }
        }
        //redirecionar pra página de login
        else if(isset($_GET['botaoPaginaLogin'])){
            header("Location: https://localhost/paginaLogin.php");
            exit();
        }
    ?>

    <img id="logo" src="https://localhost/meusOutrosArquivos/imagens/logo.png">
    <form method="post">
        <input class="camposInserirDados" id="campoUsuario" type="text" name="campoInserirUsuario">
        <input class="camposInserirDados" id="campoSenha" type="password" name="campoInserirSenha">
        <input class="camposInserirDados" id="campoConfirmarSenha" type="password" name="campoSenhaConfirmar">
        <input type="submit" id="botaoCriarConta" name="botaoCriarNovaConta" value = "Criar conta">
    </form>
    <form method="get">
        <input type="submit" id="botaoLogin" name="botaoPaginaLogin" value = "Fazer Login">
    </form>
    <p class="textosCampos" id="textoUsuário">Usuário:</p>
    <p class="textosCampos" id="textoSenha">Senha:</p>
    <p class="textosCampos" id="textoConfirmar">Confirmar Senha:</p>
</body>
</html>