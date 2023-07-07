<?php
include('protect.php');
include('conexao.php');



$sql_code = "SELECT * FROM pizza";
$sql_query = $mysqli->query($sql_code) or die("Erro ao consultar catálogo de pizzas! " . $mysqli->error);
$qnt = $sql_query->num_rows;
$hideen = 0;
    
    if (isset($_POST['ofertas'])) {
        $hideen = 1;
        $sql_code = "SELECT * FROM pizza GROUP BY Preco HAVING Preco < 500";
        $sql_query = $mysqli->query($sql_code) or die("Erro ao consultar catálogo de pizzas! " . $mysqli->error);
        $qnt = $sql_query->num_rows;
    } 

    if (isset($_POST['submeter'])) {
        if(!empty($_POST['ingredientes1'])&&!empty($_POST['ingredientes2'])&&!empty($_POST['ingredientes3'])){
            $ing1 = $_POST['ingredientes1'];
            $ing2 = $_POST['ingredientes2'];
            $ing3 = $_POST['ingredientes3'];
            $sql_monta_pizza = $mysqli->prepare("INSERT INTO `pizza` (`Nome`, `Ingredientes`, `Imagem`, `Tipo`, `Tamanho`, `Preco`) VALUES ('Pizza Montada', 'Massa, Molho, Mussarela, " . $ing1 . ", " . $ing2 . "', 'pizzaMontada.jpg', 'Montada', '" . $ing3 . "', 50))");    
            echo ("INSERT INTO `pizza` (`Nome`, `Ingredientes`, `Imagem`, `Tipo`, `Tamanho`, `Preco`) VALUES ('Pizza Montada', 'Massa, Molho, Mussarela, " . $ing1 . ", " . $ing2 . "', 'pizzaMontada.jpg', 'Montada', '" . $ing3 . "', 50)");
            $sql_monta_pizza->execute();
            $sql_select_pizza = "SELECT * from pizza where ID = (SELECT MAX(ID) FROM pizza)";
            $pizza_selecinada = $mysqli->query($sql_select_pizza)->fetch_assoc() or die("Nao foi possivel montar sua pizza! " . $mysqli->error);
            
            switch ($ing3) {
                case 'P':
                    $preco = 30;
                case 'M':
                    $preco = 40;
                case 'G':
                    $preco = 50;
                default:
                    $preco = 0;
            }
            // echo("SELECT * FROM item where IDProduto     =  " . $pizza_selecinada['ID'] . " and IDVenda = " . $_SESSION['idvenda'] . "");
            $sql_code_item = "SELECT * FROM item where IDProduto     =  " . $pizza_selecinada['ID'] . " and IDVenda = " . $_SESSION['idvenda'] . "";
            $sql_query_item = $mysqli->query($sql_code_item) or die("Erro ao consultar catálogo de produtos! " . $mysqli->error);
            $qnt_item = $sql_query_item->num_rows; 
            if ($qnt_item < 1) {
                //$sql_code_cart = $mysqli->prepare("INSERT INTO `item` (`Nome`, `Quantidade`, `PrecoItem`, `IDProduto`, `IDVenda`) VALUES ('ADFS',1,10,4,1)");
                $sql_code_cart = $mysqli->prepare("INSERT INTO `item` (`Quantidade`, `PrecoItem`, `IDProduto`, `IDVenda`) VALUES (1," . $preco . "," . $pizza_selecinada['ID'] . "," . $_SESSION['idvenda'] . ")");
                //$sql_code_cart->bind_param("siiii", null, null, null,2,'1');
                $sql_code_cart->execute();
                echo "Produto adicionado no carrinho!";
                // 
            } else {

                $sql_code_item_num = "SELECT * FROM item where IDProduto = " . $pizza_selecinada['ID'] . " and IDVenda = " . $_SESSION['idvenda'] . "";
                $sql_query_item_num = $mysqli->query($sql_code_item_num) or die("Erro ao consultar catálogo de pizza! " . $mysqli->error);
                $qnt_item_num = $sql_query_item_num->fetch_assoc();
                $resul = $sql_query_item_num->fetch_assoc();

                $sql_code_cart = $mysqli->prepare("UPDATE `item`  SET Quantidade = " . $qnt_item_num['Quantidade'] . "+1, PrecoItem = (" . $qnt_item_num['Quantidade'] . "+1) * " . $preco . " where IDProduto = " . $pizza_selecinada['ID'] . " AND IDVenda = " . $_SESSION['idvenda'] . "");
                echo "Mais uma pizza adicionado!";

                //header("Location: loja.php");
                $sql_code_cart->execute();
            }
        }
        else{
            echo ("Selecione todos os ingredientes");
        }
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <!-- Brand -->
        <a class="navbar-brand" href="loja.php">
            <img src="Imagens/logo_loja.png" alt="Logo" style="width:60px;">
        </a>
        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#carrossel">Combos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#inferior">Alimentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Autores">Autores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carrinho.php">Carrinho</a>
                </li>
                <form action="" method="post">
                    <button name=ofertas class="ofertas" type="submit"><i class="fa-sharp fa-solid fa-cart-shopping"></i>Somente as Pizzas</button>
                </form>
            </ul>
        </div>
        <form action="">
            <input name="busca" placeholder="Pesquisar" type="text">
            <button class="btn btn-light" type="submit">Pesquisar</button>
        </form>
        <!-- Toggler/collapsibe Button -->
        <div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- <div  class="col-md-6">
            <a><button href="carrinho.php" class="btn btn-light" type="submit">Carrinho</button></a>
        </div> -->
    </nav>

    <?php
    if (!isset($_GET['busca'])) {
    ?>
      <?php
        if($hideen == 0) { ?>

                <div class="lancamentos">
        </div>
        <?php
        }
        ?>
        </div>

        <!-- ITENS -->

        <div class="container" id="inferior">
            <div class="row">
                <div class="col-lg-10 col-12">
                    <h3>Produtos</h3> 
                            <div class="card mb-3" style="max-width: 700px;">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <br /><br />
                                        <img src="Imagens/" class="card-img" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"></h5>
                                            <p class="card-text"></p>
                                            <p class="card-text">Tipo: </p>
                                            <p class="card-text">R$</p>
                                            <div id="opcoes">
                                                <div class="selecao">
                                                    <form method="POST">
                                                        <h3>Escolha o seu primeiro ingrediente</h3>
                                                            <div class="ingredientes">
                                                                <input type="radio" id="ingrediente3" name="ingredientes1" value="calabresa">
                                                                <label for="ingrediente3">Calabresa</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes1" value="bacon">
                                                                <label for="ingrediente4">Bacon</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes1" value="milho">
                                                                <label for="ingrediente4">Milho</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes1" value="azeitona">
                                                                <label for="ingrediente4">Azeitona</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes1" value="tomate">
                                                                <label for="ingrediente4">Tomate</label>
                                                                </div>
                                                            </div>
                                                        <div class="selecao">
                                                        <h3>Escolha o segundo ingrediente</h3>
                                                            <div class="ingredientes">
                                                                <input type="radio" id="ingrediente3" name="ingredientes2" value="calabresa">
                                                                <label for="ingrediente3">Calabresa</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes2" value="bacon">
                                                                <label for="ingrediente4">Bacon</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes2" value="milho">
                                                                <label for="ingrediente4">Milho</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes2" value="azeitona">
                                                                <label for="ingrediente4">Azeitona</label>
                                                                <input type="radio" id="ingrediente4" name="ingredientes2" value="tomate">
                                                                <label for="ingrediente4">Tomate</label>
                                                            </div>
                                                        </div>
                                                        <div class="selecao">
                                                            <h3>Selecione o tamanho da pizza</h3>
                                                            <div class="ingredientes">
                                                                <input type="radio" id="ingrediente5" name="ingredientes3" value="P">
                                                                <label for="ingrediente5">Pequeno</label>
                                                                <input type="radio" id="ingrediente5" name="ingredientes3" value="M">
                                                                <label for="ingrediente5">Medio</label>
                                                                <input type="radio" id="ingrediente6" name="ingredientes3" value="G">
                                                                <label for="ingrediente6">Grande</label>
                                                            </div>
                                                        </div>
                                                        <input type="submit" name="submeter"></input>
                                                    </form>
                                                </div>
                                            <form action="" method="post">
                                                <button name=montada class="carrinhoBtn" type="submit"><i class="fa-sharp fa-solid fa-cart-shopping"></i>Adicionar ao carrinho</button>
                                            </form>
                                            <!-- <?php
                                            if (isset($_POST[$dados['ID']])) {
                                                
                                                
                                            }
                                            ?> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>

            </div>
        </div>
        <?php
    } //pesquisa sendo feita
    else {
        $pesquisa = $mysqli->real_escape_string($_GET['busca']);
        $sql_pesquisa = "SELECT  * FROM PIZZA WHERE Nome like '%$pesquisa%' or Ingredientes like '%$pesquisa%' or Tipo like '%$pesquisa%'";
        //$sql_pesquisa = "SELECT ID,Imagem, Nome, Descricao, Marca, Tipo, Preco FROM PRODUTO Group by Tipo Having Nome like '%$pesquisa%' or Descricao like '%$pesquisa%' or Tipo like '%$pesquisa%' or Marca like '%$pesquisa%'";
        $sql_query_pesquisa = $mysqli->query($sql_pesquisa) or die("ERRO AO CONSULTAR" . $mysqli->error);
        if ($sql_query_pesquisa->num_rows == 0) {
        ?>

            <br />
            <br />
            <td colspan="3"> Nenhum resultado encontrado...</td>
            <div class="vazio"></div>
            <br />
            <br />
            <?php
        } else {
            while ($dados = $sql_query_pesquisa->fetch_assoc()) {
            ?>
                <br />
                <div id="prodPesquisa" class="card mb-3" style="max-width: 700px;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <br /><br />
                            <img src="Imagens/<?php echo $dados['Imagem']; ?>" class="card-img" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $dados['Nome']; ?></h5>

                                <p class="card-text"><?php echo $dados['Ingredientes']; ?></p>
                                <p class="card-text">Tipo: <?php echo $dados['Tipo']; ?></p>
                                <p class="card-text">R$<?php echo $dados['Preco']; ?></p>
                                <form action="" method="post">
                                    <button name=<?php echo $dados['ID']; ?> class="carrinhoBtn" type="submit"><i class="fa-sharp fa-solid fa-cart-shopping"></i>Adicionar ao carrinho</button>
                                </form>
                                <?php
                                if (isset($_POST[$dados['ID']])) {
                                    $sql_code_item = "SELECT * FROM item where IDProduto =  " . $dados['ID'] . " and IDVenda = " . $_SESSION['idvenda'] . "";
                                    $sql_query_item = $mysqli->query($sql_code_item) or die("Erro ao consultar catálogo de pizzas! " . $mysqli->error);
                                    $qnt_item = $sql_query_item->num_rows;
                                    if ($qnt_item < 1) {
                                        //$sql_code_cart = $mysqli->prepare("INSERT INTO `item` (`Nome`, `Quantidade`, `PrecoItem`, `IDProduto`, `IDVenda`) VALUES ('ADFS',1,10,4,1)");
                                        $sql_code_cart = $mysqli->prepare("INSERT INTO `item` (`Quantidade`, `PrecoItem`, `IDProduto`, `IDVenda`) VALUES (1," . $dados['Preco'] . "," . $dados['ID'] . "," . $_SESSION['idvenda'] . ")");
                                        //$sql_code_cart->bind_param("siiii", null, null, null,2,'1');

                                        echo "Produto adicionado no carrinho!";
                                        $sql_code_cart->execute();

                                        // 
                                    } else {

                                        $sql_code_item_num = "SELECT * FROM item where IDProduto = " . $dados['ID'] . " and IDVenda = " . $_SESSION['idvenda'] . "";
                                        $sql_query_item_num = $mysqli->query($sql_code_item_num) or die("Erro ao consultar catálogo de pizzas! " . $mysqli->error);
                                        $qnt_item_num = $sql_query_item_num->fetch_assoc();
                                        $resul = $sql_query_item_num->fetch_assoc();

                                        $sql_code_cart = $mysqli->prepare("UPDATE `item`  SET Quantidade = " . $qnt_item_num['Quantidade'] . "+1, PrecoItem = (" . $qnt_item_num['Quantidade'] . "+1) * " . $dados['Preco'] . " WHERE IDProduto = " . $dados['ID'] . " AND IDVenda = " . $_SESSION['idvenda'] . "");
                                        echo "Mais um pizzas adicionado!";

                                        //header("Location: loja.php");
                                        $sql_code_cart->execute();
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

    <?php
        }
    }
    ?>
    <footer id="Autores">
        <p class="footer">Copyright © 2023 - Engenharia de Software - Celso França - Jorge Hiroki - Gabriel de Medeiros - André Macedo</p>
        <img src="Imagens/logo_loja.png">
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</body>

</html>