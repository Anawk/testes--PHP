<?php 

require_once('classes/Viagens.class.php');
require_once "classes/Encomendas.class.php";

$objViagens = new Viagens;
$objEncomendas = new Encomendas();

$Title = "Encomendas";
$title = "encomendas";
$idViagem = isset($_GET['idViagem']) ? $_GET['idViagem'] : "";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" name="content=width=device-width, inicial-scale=1">
    <meta name="google-site-verification" content="XWF3zfS-1yxkoGf-NRaHzeIIYclXah1EQNvsSeczxQc" />
    <title>Tinebus - Encomendas</title>
    <meta name="description" content="Agência especializada em Marketing Digital, Criação de Sites Aplicativos Mobile"> 
    <meta name="keywords" content="Agência digital, Marketing, Sites">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Tiago Silva">
    <!-- arquivos quando estava sem internet-->    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="all">
    <!--<link href="css/jquery-ui.min.css" rel="stylesheet">-->

    <!-- fim arquivos quando estava sem internet-->    
    <link href="css/css_onibus.css" rel="stylesheet">
    <link rel="stylesheet" href="css/man.css">
    <link rel="stylesheet" href="css/animade.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

    
    <style>
        @media print {

            .numero_encomenda {
                  background-color: red !important;
                  color: white !important;
                  text-align: center !important;
            }

            * {
                font-size: 12px !important;
                margin: 0;
                padding: 0;
                line-height: unset;
                font-size: 10px !important;
            }

            td.numero_par {
                background-color: #cccccc57 !important;
                -webkit-print-color-adjust: exact;

            }

            td.cor {

                background-color: red !important;
                -webkit-print-color-adjust: exact;

            }

            .table-bordered>thead>tr>td,
            .table-bordered>thead>tr>th {
                border-bottom-width: 0px;
            }

            .table-bordered {
                border: 1px solid #ddd !important;
            }

             table tbody tr.zebra-branco {
                background-color: #ffffff !important; 
                 -webkit-print-color-adjust: exact;
            }

             table tbody tr.zebra-azul {
                background-color: red !important; 
                 -webkit-print-color-adjust: exact;     
             }

            /* .table-striped>tbody>tr:nth-of-type(odd) {
                background-color: #cccccc57 !important;
                -webkit-print-color-adjust: exact;
            } */
           
        
        }


        
.table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 0px;
            padding: 2px 0 2px 10px;
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th {
            border: 1px solid #000 !important;
        }

        .cor {
            background-color: red !important;


        }

        td.numero_par {
            background-color: #cccccc57 !important;
            -webkit-print-color-adjust: exact;

        }

        @page {
            margin: 0;
            padding: 0;

        }

        .table-bordered {
            border: 1px solid #ddd !important;
        }

         table tbody tr.zebra-branco {
            background-color: #ffffff  !important ; 
             -webkit-print-color-adjust: exact;
        }

        table tbody tr.zebra-azul {
            background-color: #bdd9e6 !important ;
             -webkit-print-color-adjust: exact;
             /* ou background-color: #bdd9e6*/
        }
       
    </style>
</head>


<body>
<br>

<a style="padding-left:10px" onclick="Imprimir();" id="btnimprimir" class="btn btn-adicionar btn-primary">
    Imprimir
</a>
<p style="padding-left:10px"> </p>

<table id="myTabledd" class="table table-striped table-bordered text-uppercase display nowrap" style="width: 96%; margin-left:2%">


    <thead>
    <tr>
        <th scope="col" colspan="6" style="padding-left:2px">
            <p class="text-center bold" style="font-weight: bold;"> RELAÇÃO DE ENCOMENDAS
                PORTAL SUL VIAGENS
            </p>
        </th>
    </tr>
    <tr>
        <th scope="col" colspan="6" >Data: ___/___/______</th>
    </tr>
    <tr class="vendorListHeading">
    <th scope="col" style="width: 4%; background-color: red; color: white;" class="numero_encomenda">COD</th> 
        <th scope="col">PARA</th>
        <th scope="col">CIDADE</th>
        <th scope="col">QTD</th>
        <th scope="col">VALOR</th>
        <th scope="col">PAGO</th>
    </tr>
</thead>

<?php
// número total de encomendas
$total_encomendas = count($objEncomendas->findAllEncomendas($idViagem));

// Define a altura extra das linhas vazias
$altura_linha_extra = 30; // Altura extra em pixels

// Determina o número do próximo código de encomenda
$proximo_codigo_encomenda = $total_encomendas + 1;
?>

    <tbody>
    <?php 
        $class = '';

        // Armazena a numeração das encomendas
        $numero_encomenda = 1;

        // Iteração sobre as encomendas
        foreach($objEncomendas->findAllEncomendas($idViagem) as $key => $value) {
            $class = ($class == 'zebra-branco') ? 'zebra-azul' : 'zebra-branco';
    ?>          
        <tr class="<?php echo $class; ?>">
            <td style="background-color: red; color: white; width: 4%;"><?php echo $numero_encomenda++; ?></td>
            <td style="min-width: 100px !important; max-width: 200px !important"><?php echo $value->bus_ecomenda_destinatario; ?></td>
            <td style="width: 150px "><?php echo $value->bus_local_cidade_destino; ?></td>
            <td><?php echo $value->bus_encomenda_qtd; ?></td> 
            <td style="width: 50px ">R$ <?php echo number_format($value->bus_ecomenda_valor,2,",",".");?></td>
            <td class="teste" style="width: 50px "><?php echo  (isset($value->bus_ecomenda_pago) && $value->bus_ecomenda_pago == "s") ? "Sim" : "Não" ;?></td>
        </tr>
    <?php } ?>

    <!-- Adicionando 10 linhas vazias -->
   <?php for ($i = 0; $i < 10; $i++): ?>
    <tr style="height: <?php echo $altura_linha_extra; ?>px;">
        <td style="background-color: red; color: white; width: 4%;"><?php echo $proximo_codigo_encomenda++; ?></td>
        <td style="min-width: 100px !important; max-width: 200px !important"></td>
        <td style="width: 150px "></td>
        <td></td>
        <td style="width: 100px ">R$ </td> <!-- Adicionando "R$" -->
        <td style="width: 50px "></td>
    </tr>
    <?php endfor; ?>
</tbody>
</table>
   


<script>
        

        function Imprimir() {
        document.getElementById("btnimprimir").style.display = "none";


        // document.getElementById("Tiradoimprimir").style.display = "none";

        window.print();
    }
    </script>
</body>
</html>


