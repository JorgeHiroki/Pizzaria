<?php
include('protect.php');
include('conexao.php');

if(isset($_POST['cancelar'])){
    $sql_cancelar = $mysqli->query("DELETE from venda where ID = ".$_SESSION['idvenda']."") or die("Erro ao consultar catálogo de produtos! " . $mysqli->error);
    //$sql_cancelar->execute();
    echo 'iamsf sdafdsuafhdsa';
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta lang="PT-BR">
    <title>Pizzaria Felipe Massas</title>
    <link rel="sortcut icon" href="Imagens/logo_loja.png" type="image/png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
    <link rel="stylesheet" href="loja.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styleLogin.css" media="screen" />
</head>

<body>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <!-- Brand -->
        <a class="navbar-brand" href="#">
            <img src="Imagens/logo_loja.png" alt="Logo" style="width:60px;">
        </a>
        <!-- Navbar links -->
        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    </div>

    <!-- ITENS -->
    <?php
     $sql_code = "SELECT IDProduto, Quantidade, PrecoItem, IDVenda, p.Nome FROM item i left outer join pizza p on i.IDProduto = p.ID where IDVenda = ".$_SESSION['idvenda']."";
    $sql_query = $mysqli->query($sql_code) or die("Erro ao consultar catálogo de produtos! " . $mysqli->error);
    $qnt = $sql_query->num_rows;

    
    ?>
    <div class="container" id="inferior">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h1>Produtos</h1>

                <div class="divBotoes" style="background-color: white;">
                    <div class="d-flex justify-content-center mt-3 pay_container" style="margin-left: 1005px;">
                        <button type="submit" name="pagar" class="btn pay_btn"><a href="pagamento.php" style="color: white;">Pagar</a></button>
                    </div>
                    <form action="" method="post">
                    <div class="d-flex justify-content-center mt-3 pay_container" style="margin-left: 1005px;">
                        <button type="submit" name="cancelar" class="btn pay_btn">Cancelar compra</a></button>
                    </div>
                    </form>
                    <div class="d-flex justify-content-center mt-3 pay_container" style="margin-left: 1005px;">
                        <button type="submit" name="voltarLoja" class="btn pay_btn"><a href="loja.php" style="color: white;">Voltar para loja</a></button>
                    </div>
                </div>

                <!-- FAZER UM IF COM ALGO COMO NADA CADSTRADO SE NAO TIVER ITENS-->
                <?php
                
                if ($qnt < 1) {
                ?>
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <h5 class="card-title">Não há produtos no carrinho</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- else aqui-->
                    <?php
                } else {
                    
                    while ($dados = $sql_query->fetch_assoc()) {
                    ?>
                        <div class="card mb-3" style="max-width: 540px;">
                            <div class="row no-gutters">
                                <div class="col-lg-12">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $dados['Nome']; ?></h5>
                                        <p class="card-text">Quantidade: <?php echo $dados['Quantidade']; ?></p>
                                        <p class="card-text">R$ <?php echo $dados['PrecoItem']; ?>,00</p>
                                        <p class="card-text">Id do produto: <?php echo $dados['IDProduto']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
                <div>
                    <h3><b>Total da compra: R$</b> <?php
                                                $sql_code2 = "SELECT * FROM item where IDVenda = ".$_SESSION['idvenda']."";
                                                $sql_query2 = $mysqli->query($sql_code) or die("Erro ao consultar catálogo de produtos! " . $mysqli->error);
                                                $item = $sql_query2->fetch_assoc();
                                    
                                                if (isset($item)) {
                                                    $itemId = $item['IDVenda'];
                                                    $sql_codeCompra = "SELECT PrecoTotal FROM venda where ID = ".$_SESSION['idvenda']." ";
                                                    $sql_queryTotalCompra = $mysqli->query($sql_codeCompra) or die("Erro ao consultar preço total da venda! " . $mysqli->error);
                                                    $preco = $sql_queryTotalCompra->fetch_assoc();
                                                    echo $preco['PrecoTotal'];
                                                }
                                                ?></h3>
                </div>  
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>