<?php
include('protect.php');
include('conexao.php');
//echo $_SESSION['nome'];

if (isset($_POST['tipo']) || isset($_POST['nome']) || isset($_POST['numero']) || isset($_POST['dataExp']) || isset($_POST['cvv']) || isset($_POST['cpf']) || isset($_POST['endereco'])) {
  
  //limpando as variavei
  $tipo = $mysqli->real_escape_string($_POST['tipo']);
  $nome = $mysqli->real_escape_string($_POST['nome']);
  $numero = $mysqli->real_escape_string($_POST['numero']);
  $dataexp = $mysqli->real_escape_string($_POST['dataexp']);
  $cvv = $mysqli->real_escape_string($_POST['cvv']);
  $endereco = $mysqli->real_escape_string($_POST['endereco']);
  $cpf = $_SESSION['cpf'];
  $id =  $_SESSION['idvenda'];
  
  echo $_SESSION['idvenda'];
  $sql_code = $mysqli->prepare("INSERT INTO `formapagamento` (`ID`,`Tipo`, `Nome`, `Numero`, `DataExp`, `CVV`,`CPFCliente`) VALUES (?,?,?,?,?,?,?)");
  $sql_code->bind_param("issssss",$_SESSION['idvenda'],$tipo, $nome, $numero, $dataexp, $cvv,$cpf);
  $sql_code->execute();
  
  $sql_code_endereco= $mysqli->query("UPDATE `venda`  SET `Endereco` = '".$endereco."' WHERE ID = ".$_SESSION['idvenda']."") or die("ESSE ERRO2: " . $mysqli->error);  

  $sql_code_endereco= $mysqli->query("UPDATE `venda`  SET `IDFormaPagamento` = ".$_SESSION['idvenda']." WHERE ID = ".$_SESSION['idvenda']."") or die("ESSE ERRO3: " . $mysqli->error);    

  header("Location: final.php");
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UFT-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta lang="PT-BR">
  <title>Cadastrar pagamento</title>
  <link rel="sortcut icon" href="Imagens/logo_loja.png" type="image/png" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
  <link rel="stylesheet" href="payment.css">
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="#">
      <img src="Imagens/logo_loja.png" alt="Logo" style="width:60px;">
    </a>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
      </ul>
    </div>
    <!-- Toggler/collapsibe Button -->
  </nav>

  <div style="margin-left: 10px;">
    <form action="" method="POST">
      <div class="dropdown">
        <h4 class="mb-3">Pagamento</h4>
        <select name="tipo">
          <option value="debito">Cartao de Debito</option>
          <option value="credito" selected>Cartao de Credito</option>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label for="cc-nome">Nome no cartão</label>
        <input type="text" name="nome" class="form-control" id="cc-nome" placeholder="" required="">
        <small class="text-muted">Nome completo, como mostrado no cartão.</small>
        <div class="invalid-feedback">
          O nome que está no cartão é obrigatório.
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <label for="cc-numero">Número do cartão de crédito</label>
        <input type="text" name="numero" class="form-control" id="cc-numero" placeholder="" required="">

      </div>
      <div class="row">
        <div class="col-md-3 mb-3">
          <label for="cc-expiracao">Data de expiração</label>
          <input type="text" name="dataexp" class="form-control" id="cc-expiracao" placeholder="" required="">
          <div class="invalid-feedback">
            Data de expiração é obrigatória.
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <label for="cc-cvv">CVV</label>
          <input type="text" name="cvv" class="form-control" id="cc-cvv" placeholder="" required="">
          <div class="invalid-feedback">
            Código de segurança é obrigatório.
          </div>
        </div>
        <hr class="mb-4">
      </div>
      <div class="col-md-6 mb-3">
        <label for="cc-nome">Endereço</label>
        <input type="text" name="endereco" class="form-control" id="cc-nome" placeholder="" required="">
        <small class="text-muted">Endereço de entrega</small>
        <div class="invalid-feedback">
          O endereço é obrigatório.
        </div>
      </div>
      <button name="botaoFinalizar" class="btn btn-primary btn-lg btn-block" type="submit" style="background-color: #ec600f;">Finalizar pagamento</button>
    </form>
    
  </div>

</body>

</html>