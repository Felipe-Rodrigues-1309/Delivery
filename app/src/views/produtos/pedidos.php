<?php
date_default_timezone_set('America/Fortaleza');
require_once __DIR__ . '/../../config/conexao.php';
$conn->query("SET time_zone = '-03:00'");

$mensagem = null;

/*
|--------------------------------------------------------------------------
| AÇÕES DOS PEDIDOS
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | EXCLUIR PEDIDO
    |--------------------------------------------------------------------------
    */
    if (isset($_POST['excluir_pedido'])) {

        $id = intval($_POST['id']);

        $sql = "DELETE FROM pedido WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            $mensagem = "Pedido #{$id} excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir o pedido!";
        }

        $stmt->close();
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR STATUS
    |--------------------------------------------------------------------------
    */
    if (
        isset($_POST['id']) &&
        isset($_POST['status']) &&
        !isset($_POST['atualizar_financeiro']) &&
        !isset($_POST['excluir_pedido'])
    ) {

        $id = intval($_POST['id']);
        $status = trim($_POST['status']);

        $sql = "UPDATE pedido SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);

        if ($stmt->execute()) {
            $mensagem = "Status atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar status!";
        }

        $stmt->close();
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR FINANCEIRO
    |--------------------------------------------------------------------------
    */
    if (isset($_POST['atualizar_financeiro'])) {

        $id = intval($_POST['id']);
        $pagamento = trim($_POST['pagamento'] ?? '');
        $pago = isset($_POST['pago']) ? 1 : 0;


        /*
        |--------------------------------------------------------------------------
        | SE FOI PAGO
        | Salva automaticamente data e hora
        |--------------------------------------------------------------------------
        */
        if ($pago === 1) {

            $sql = "UPDATE pedido
                    SET pagamento = ?,
                        pago = 1,
                        data_pagamento = NOW()
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $pagamento, $id);

        } else {

            /*
            |--------------------------------------------------------------------------
            | SE NÃO FOI PAGO
            | Remove a data de pagamento
            |--------------------------------------------------------------------------
            */

            $sql = "UPDATE pedido
                    SET pagamento = ?,
                        pago = 0,
                        data_pagamento = NULL
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $pagamento, $id);
        }


        if ($stmt->execute()) {
            $mensagem = "Informações financeiras atualizadas com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar informações financeiras!";
        }

        $stmt->close();
    }
}


/*
|--------------------------------------------------------------------------
| BUSCAR PEDIDOS
|--------------------------------------------------------------------------
*/
$sql = "SELECT * FROM pedido ORDER BY data_pedido DESC";
$result = $conn->query($sql);

$pedidosNovos = [];
$pedidosEmPreparo = [];
$pedidosSaiuEntrega = [];
$pedidosConcluidos = [];


/*
|--------------------------------------------------------------------------
| RESUMO FINANCEIRO
|--------------------------------------------------------------------------
*/
$totalVendido = 0;
$totalRecebido = 0;
$totalPendente = 0;

$quantidadePagos = 0;
$quantidadePendentes = 0;


if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $valor = floatval($row['valor'] ?? 0);

        $totalVendido += $valor;


        if (isset($row['pago']) && intval($row['pago']) === 1) {

            $totalRecebido += $valor;
            $quantidadePagos++;

        } else {

            $totalPendente += $valor;
            $quantidadePendentes++;
        }


        /*
        |--------------------------------------------------------------------------
        | ORGANIZAR PEDIDOS POR STATUS
        |--------------------------------------------------------------------------
        */

        $status = strtolower(trim($row['status'] ?? ''));


        if ($status === '') {

            $pedidosNovos[] = $row;

        } elseif ($status === 'em preparo') {

            $pedidosEmPreparo[] = $row;

        } elseif ($status === 'saiu para entrega') {

            $pedidosSaiuEntrega[] = $row;

        } elseif (
            $status === 'concluído' ||
            $status === 'concluido' ||
            $status === 'entregue'
        ) {

            $pedidosConcluidos[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <a
    href="https://felvix.infinityfreeapp.com/encomendasDutra/app/public/index.php?action=dashboard"
    class="btn btn-menu no-print"
>
    🏠 Ir para o Menu
</a>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>


    <style>

        /* =====================================================
           BODY
        ===================================================== */

        body {

            background: #020024;

            color: white;

        }


        .btn-menu {
    color: #00ff00;
    border: 1px solid #00ff00;
    background: rgba(0, 255, 0, 0.05);
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-menu:hover {
    background: #00ff00;
    color: #000;
    box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
    transform: translateY(-2px);
}
        /* =====================================================
           CARDS DOS PEDIDOS
        ===================================================== */

        .pedido-card {

            color: white;

            background: rgba(15, 23, 42, 0.35);

            backdrop-filter: blur(15px);

            -webkit-backdrop-filter: blur(15px);

            box-shadow: 0 1px 15px #00ff00;

            border: 2px solid #00ff00;

            margin-bottom: 30px;

        }


        /* =====================================================
           PAINEL FINANCEIRO
        ===================================================== */

        .finance-card {

            color: white;

            background: rgba(15, 23, 42, 0.55);

            backdrop-filter: blur(15px);

            -webkit-backdrop-filter: blur(15px);

            border: 2px solid #00ff00;

            border-radius: 15px;

            padding: 20px;

            display: flex;

            align-items: center;

            gap: 15px;

            min-height: 120px;

            box-shadow: 0 5px 20px rgba(0, 255, 0, 0.20);

            transition: 0.3s;

        }


        .finance-card:hover {

            transform: translateY(-3px);

            box-shadow: 0 8px 25px rgba(0, 255, 0, 0.35);

        }


        .finance-card.recebido {

            border-color: #00ff88;

        }


        .finance-card.pendente {

            border-color: #ffc107;

        }


        .finance-card.pedidos {

            border-color: #0dcaf0;

        }


        .finance-icon {

            font-size: 35px;

            min-width: 45px;

        }


        .finance-card small {

            color: #aaa;

            display: block;

        }


        .finance-card h4 {

            margin: 4px 0;

            font-weight: bold;

        }


        .finance-card span {

            color: #aaa;

            font-size: 13px;

        }


        /* =====================================================
           CONTROLE FINANCEIRO
        ===================================================== */

        .finance-box {

            background: rgba(0, 0, 0, 0.30);

            border: 1px solid rgba(0, 255, 0, 0.4);

            border-radius: 12px;

            padding: 15px;

            margin-top: 15px;

        }


        .finance-box h6 {

            color: #00ff00;

            font-weight: bold;

        }


        .status-pagamento {

            font-size: 13px;

            padding: 6px 10px;

            border-radius: 20px;

        }


        .pago {

            background: #198754;

            color: white;

        }


        .nao-pago {

            background: #dc3545;

            color: white;

        }


        /* =====================================================
           DATA DO PAGAMENTO
        ===================================================== */

        .data-pagamento {

            display: block;

            margin-top: 8px;

            color: #00ff88;

            font-size: 13px;

        }


        /* =====================================================
           BOTÃO EXCLUIR
        ===================================================== */

        .btn-excluir {

            border: 1px solid #dc3545;

            color: #ff6b6b;

            background: transparent;

            transition: 0.3s;

        }


        .btn-excluir:hover {

            background: #dc3545;

            color: white;

        }


        /* =====================================================
           ABAS
        ===================================================== */

        .nav-tabs {

            border-bottom: 1px solid #00ff00;

        }


        .nav-tabs .nav-link {

            color: white;

        }


        .nav-tabs .nav-link:hover {

            color: #00ff00;

        }


        .nav-tabs .nav-link.active {

            background: #00ff00;

            color: #000;

            border-color: #00ff00;

        }


        /* =====================================================
           PRODUTOS
        ===================================================== */

        .produto-area {

            background: rgba(0, 0, 0, 0.25);

            padding: 10px;

            border-radius: 8px;

        }


        /* =====================================================
           IMPRESSÃO
        ===================================================== */

        @page {

            size: 58mm auto;

            margin: 0;

        }


        @media print {

            html,
            body {

                width: 58mm !important;

                margin: 0 !important;

                padding: 2mm !important;

                background: #fff !important;

                color: #000 !important;

                font-family: Consolas, "Courier New", monospace;

                font-size: 11px;

            }


            .container {

                width: 58mm !important;

                max-width: 58mm !important;

                margin: 0 !important;

                padding: 0 !important;

            }


            .pedido-card {

                width: 100% !important;

                border: none !important;

                box-shadow: none !important;

                background: #fff !important;

                color: #000 !important;

                page-break-inside: avoid;

            }


            .card-header {

                background: #fff !important;

                color: #000 !important;

                border-bottom: 1px dashed #000;

            }


            .btn,
            .no-print,
            .nav,
            .nav-tabs,
            h2 {

                display: none !important;

            }


            hr {

                border-top: 1px dashed #000;

            }

        }


        /* =====================================================
           ANIMAÇÃO
        ===================================================== */

        @keyframes pulse {

            0%,
            100% {

                transform: scale(1);

            }

            50% {

                transform: scale(1.2);

            }

        }


        #novosPedidosBadge {

            display: inline-block;

        }

    </style>

</head>


<body>


<div class="container mt-4">


    <!-- =====================================================
         TÍTULO
    ===================================================== -->

    <h2 style="color:white;" class="mb-4">

        📋 Pedidos

    </h2>


    <!-- =====================================================
         MENSAGEM
    ===================================================== -->

    <?php if (isset($mensagem)): ?>

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <?= htmlspecialchars($mensagem) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         RESUMO FINANCEIRO
    ===================================================== -->

    <div class="row g-3 mb-4 no-print">


        <div class="col-12 col-md-6 col-lg-3">

            <div class="finance-card">

                <div class="finance-icon">
                    💰
                </div>

                <div>

                    <small>
                        Total vendido
                    </small>

                    <h4>

                        R$
                        <?= number_format(
                            $totalVendido,
                            2,
                            ',',
                            '.'
                        ) ?>

                    </h4>

                    <span>
                        Todas as vendas
                    </span>

                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 col-lg-3">

            <div class="finance-card recebido">

                <div class="finance-icon">
                    ✅
                </div>

                <div>

                    <small>
                        Total recebido
                    </small>

                    <h4>

                        R$
                        <?= number_format(
                            $totalRecebido,
                            2,
                            ',',
                            '.'
                        ) ?>

                    </h4>

                    <span>

                        <?= $quantidadePagos ?>

                        pedidos pagos

                    </span>

                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 col-lg-3">

            <div class="finance-card pendente">

                <div class="finance-icon">
                    ⏳
                </div>

                <div>

                    <small>
                        Total pendente
                    </small>

                    <h4>

                        R$
                        <?= number_format(
                            $totalPendente,
                            2,
                            ',',
                            '.'
                        ) ?>

                    </h4>

                    <span>

                        <?= $quantidadePendentes ?>

                        pedidos pendentes

                    </span>

                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 col-lg-3">

            <div class="finance-card pedidos">

                <div class="finance-icon">
                    📦
                </div>

                <div>

                    <small>
                        Total de pedidos
                    </small>

                    <h4>

                        <?= $quantidadePagos + $quantidadePendentes ?>

                    </h4>

                    <span>

                        <?= $quantidadePagos ?> pagos

                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         ABAS
    ===================================================== -->

    <ul
        class="nav nav-tabs mb-4 no-print"
        role="tablist"
    >

        <li class="nav-item">

            <button
                class="nav-link active"
                id="novos-tab"
                data-bs-toggle="tab"
                data-bs-target="#novos"
                type="button"
            >

                🔴 Novos

                <span
                    class="badge bg-danger"
                    id="novosPedidosBadge"
                >

                    <?= count($pedidosNovos) ?>

                </span>

            </button>

        </li>


        <li class="nav-item">

            <button
                class="nav-link"
                id="preparo-tab"
                data-bs-toggle="tab"
                data-bs-target="#preparo"
                type="button"
            >

                👨‍🍳 Em Preparo

                <span class="badge bg-warning text-dark">

                    <?= count($pedidosEmPreparo) ?>

                </span>

            </button>

        </li>


        <li class="nav-item">

            <button
                class="nav-link"
                id="entrega-tab"
                data-bs-toggle="tab"
                data-bs-target="#entrega"
                type="button"
            >

                🚚 Saiu para Entrega

                <span class="badge bg-info">

                    <?= count($pedidosSaiuEntrega) ?>

                </span>

            </button>

        </li>


        <li class="nav-item">

            <button
                class="nav-link"
                id="concluido-tab"
                data-bs-toggle="tab"
                data-bs-target="#concluido"
                type="button"
            >

                ✅ Concluído

                <span class="badge bg-success">

                    <?= count($pedidosConcluidos) ?>

                </span>

            </button>

        </li>

    </ul>


    <div class="tab-content">


        <!-- =================================================
             FUNÇÃO PARA EXIBIR CONTROLE FINANCEIRO
        ================================================= -->

        <?php
        /*
        NOTA:
        O bloco financeiro continua sendo exibido individualmente
        dentro de cada pedido.
        */
        ?>


        <!-- =================================================
             NOVOS
        ================================================= -->

        <div
            class="tab-pane fade show active"
            id="novos"
            role="tabpanel"
        >

            <?php if (count($pedidosNovos) > 0): ?>

                <?php foreach ($pedidosNovos as $pedido): ?>

                    <div
                        class="pedido-card card"
                        id="pedido-<?= $pedido['id'] ?>"
                    >

                        <div class="card-header bg-danger text-white">

                            <div class="row align-items-center">

                                <div class="col">

                                    <h6 class="mb-0">

                                        <span class="badge bg-light text-dark">
                                            NOVO
                                        </span>

                                        <strong>
                                            Pedido #<?= $pedido['id'] ?>
                                        </strong>

                                        <small>

                                            <?= date(
                                                'd/m/Y H:i',
                                                strtotime($pedido['data_pedido'])
                                            ) ?>

                                        </small>

                                    </h6>

                                </div>


                                <div class="col-auto no-print">

                                    <button
                                        class="btn btn-sm btn-light"
                                        onclick="imprimirPedido(<?= $pedido['id'] ?>)"
                                    >
                                        🖨️
                                    </button>


                                    <form
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirmarExclusao(<?= $pedido['id'] ?>)"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="excluir_pedido"
                                            value="1"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            🗑️ Excluir
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">


                            <!-- CLIENTE -->

                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <p>

                                        <strong>👤 Cliente:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['nome'] ?? 'N/A'
                                        ) ?>

                                    </p>


                                    <p>

                                        <strong>📞 Telefone:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['telefone'] ?? 'N/A'
                                        ) ?>

                                    </p>

                                </div>


                                <div class="col-md-6">

                                    <p>

                                        <strong>💳 Pagamento:</strong>

                                        <span class="badge bg-info">

                                            <?= htmlspecialchars(
                                                $pedido['pagamento'] ?? 'Não especificado'
                                            ) ?>

                                        </span>

                                    </p>

                                </div>

                            </div>


                            <hr>


                            <!-- ENDEREÇO -->

                            <div class="mb-3">

                                <strong>
                                    📍 Endereço de Entrega:
                                </strong>

                                <div class="produto-area mt-2">

                                    <?php if (
                                        !empty($pedido['rua']) ||
                                        !empty($pedido['numero']) ||
                                        !empty($pedido['bairro']) ||
                                        !empty($pedido['cidade'])
                                    ): ?>

                                        <p class="mb-1">

                                            <?= htmlspecialchars(
                                                $pedido['rua'] ?? ''
                                            ) ?>,

                                            <?= htmlspecialchars(
                                                $pedido['numero'] ?? ''
                                            ) ?>

                                            -

                                            <?= htmlspecialchars(
                                                $pedido['bairro'] ?? ''
                                            ) ?>,

                                            <?= htmlspecialchars(
                                                $pedido['cidade'] ?? ''
                                            ) ?>

                                        </p>


                                        <?php if (
                                            !empty($pedido['ponto_de_referencia'])
                                        ): ?>

                                            <p class="mb-0">

                                                <small>

                                                    Referência:

                                                    <?= htmlspecialchars(
                                                        $pedido['ponto_de_referencia']
                                                    ) ?>

                                                </small>

                                            </p>

                                        <?php endif; ?>

                                    <?php else: ?>

                                        <p class="text-muted">
                                            Endereço não informado
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <hr>


                            <!-- PRODUTOS -->

                            <div class="mb-3">

                                <strong>
                                    🛒 Produtos:
                                </strong>

                                <div class="produto-area mt-2">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $pedido['item'] ?? 'Sem descrição'
                                        )
                                    ) ?>

                                </div>

                            </div>


                            <hr>


                            <!-- TOTAL -->

                            <div class="row align-items-center">

                                <div class="col">

                                    <h5>

                                        💰 Total:

                                        <span class="text-success">

                                            R$

                                            <?= number_format(
                                                $pedido['valor'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>

                                        </span>

                                    </h5>

                                </div>


                                <div class="col-auto no-print">

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <select
                                            name="status"
                                            class="form-select form-select-sm d-inline-block w-auto"
                                        >

                                            <option value="">
                                                Mudar status...
                                            </option>

                                            <option value="em preparo">
                                                👨‍🍳 Em Preparo
                                            </option>

                                            <option value="saiu para entrega">
                                                🚚 Saiu para Entrega
                                            </option>

                                            <option value="concluído">
                                                ✅ Concluído
                                            </option>

                                        </select>


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary ms-2"
                                        >
                                            Atualizar
                                        </button>

                                    </form>

                                </div>

                            </div>


                            <!-- FINANCEIRO -->

                            <div class="finance-box no-print">

                                <h6>
                                    💳 Controle financeiro
                                </h6>


                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $pedido['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="atualizar_financeiro"
                                        value="1"
                                    >


                                    <div class="row g-3 align-items-end">


                                        <div class="col-12 col-md-5">

                                            <label class="form-label">
                                                Forma de pagamento
                                            </label>

                                            <select
                                                name="pagamento"
                                                class="form-select"
                                            >

                                                <option value="">
                                                    Selecione...
                                                </option>

                                                <option
                                                    value="Pix"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Pix') ? 'selected' : '' ?>
                                                >
                                                    📱 Pix
                                                </option>

                                                <option
                                                    value="Dinheiro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Dinheiro') ? 'selected' : '' ?>
                                                >
                                                    💵 Dinheiro
                                                </option>

                                                <option
                                                    value="Em Aberto"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Em Aberto') ? 'selected' : '' ?>
                                                >
                                                    Em Aberto
                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-12 col-md-4">

                                            <label class="form-label">
                                                Situação do pagamento
                                            </label>

                                            <div class="form-check form-switch">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="pago"
                                                    value="1"
                                                    id="pago<?= $pedido['id'] ?>novos"
                                                    <?= !empty($pedido['pago']) ? 'checked' : '' ?>
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="pago<?= $pedido['id'] ?>novos"
                                                >

                                                    <?php if (!empty($pedido['pago'])): ?>

                                                        <span class="status-pagamento pago">
                                                            ✅ Pago
                                                        </span>

                                                        <?php if (!empty($pedido['data_pagamento'])): ?>

                                                            <span class="data-pagamento">

                                                                📅 Pago em:

                                                                <?= date(
                                                                    'd/m/Y H:i',
                                                                    strtotime($pedido['data_pagamento'])
                                                                ) ?>

                                                            </span>

                                                        <?php endif; ?>

                                                    <?php else: ?>

                                                        <span class="status-pagamento nao-pago">
                                                            ❌ Não pago
                                                        </span>

                                                    <?php endif; ?>

                                                </label>

                                            </div>

                                        </div>


                                        <div class="col-12 col-md-3">

                                            <button
                                                type="submit"
                                                class="btn btn-success w-100"
                                            >
                                                💾 Salvar
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="alert alert-info">
                    Nenhum pedido novo no momento! 🎉
                </div>

            <?php endif; ?>

        </div>


        <!-- =================================================
             EM PREPARO
        ================================================= -->

        <div
            class="tab-pane fade"
            id="preparo"
            role="tabpanel"
        >

            <?php if (count($pedidosEmPreparo) > 0): ?>

                <?php foreach ($pedidosEmPreparo as $pedido): ?>

                    <div
                        class="pedido-card card"
                        id="pedido-<?= $pedido['id'] ?>"
                    >

                        <div class="card-header bg-warning">

                            <div class="row align-items-center">

                                <div class="col">

                                    <h6 class="mb-0">

                                        <span class="badge bg-dark">
                                            EM PREPARO
                                        </span>

                                        <strong>
                                            Pedido #<?= $pedido['id'] ?>
                                        </strong>

                                        <small>

                                            <?= date(
                                                'd/m/Y H:i',
                                                strtotime($pedido['data_pedido'])
                                            ) ?>

                                        </small>

                                    </h6>

                                </div>


                                <div class="col-auto no-print">

                                    <button
                                        class="btn btn-sm btn-light"
                                        onclick="imprimirPedido(<?= $pedido['id'] ?>)"
                                    >
                                        🖨️
                                    </button>


                                    <form
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirmarExclusao(<?= $pedido['id'] ?>)"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="excluir_pedido"
                                            value="1"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            🗑️ Excluir
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <p>
                                        <strong>👤 Cliente:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['nome'] ?? 'N/A'
                                        ) ?>
                                    </p>

                                    <p>
                                        <strong>📞 Telefone:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['telefone_cliente'] ?? 'N/A'
                                        ) ?>
                                    </p>

                                </div>


                                <div class="col-md-6">

                                    <p>

                                        <strong>💳 Pagamento:</strong>

                                        <span class="badge bg-info">

                                            <?= htmlspecialchars(
                                                $pedido['pagamento'] ?? 'Não especificado'
                                            ) ?>

                                        </span>

                                    </p>

                                </div>

                            </div>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    📍 Endereço de Entrega:
                                </strong>

                                <div class="produto-area mt-2">

                                    <p class="mb-1">

                                        <?= htmlspecialchars(
                                            $pedido['rua'] ?? ''
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $pedido['numero'] ?? ''
                                        ) ?>

                                        -

                                        <?= htmlspecialchars(
                                            $pedido['bairro'] ?? ''
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $pedido['cidade'] ?? ''
                                        ) ?>

                                    </p>


                                    <?php if (
                                        !empty($pedido['ponto_de_referencia'])
                                    ): ?>

                                        <p class="mb-0">

                                            <small>

                                                Referência:

                                                <?= htmlspecialchars(
                                                    $pedido['ponto_de_referencia']
                                                ) ?>

                                            </small>

                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    🛒 Produtos:
                                </strong>

                                <div class="produto-area mt-2">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $pedido['item'] ?? 'Sem descrição'
                                        )
                                    ) ?>

                                </div>

                            </div>


                            <hr>


                            <div class="row align-items-center">

                                <div class="col">

                                    <h5>

                                        💰 Total:

                                        <span class="text-success">

                                            R$

                                            <?= number_format(
                                                $pedido['valor'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>

                                        </span>

                                    </h5>

                                </div>


                                <div class="col-auto no-print">

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <select
                                            name="status"
                                            class="form-select form-select-sm d-inline-block w-auto"
                                        >

                                            <option value="">
                                                Mudar status...
                                            </option>

                                            <option value="saiu para entrega">
                                                🚚 Saiu para Entrega
                                            </option>

                                            <option value="concluído">
                                                ✅ Concluído
                                            </option>

                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary ms-2"
                                        >
                                            Atualizar
                                        </button>

                                    </form>

                                </div>

                            </div>


                            <!-- FINANCEIRO -->

                            <div class="finance-box no-print">

                                <h6>
                                    💳 Controle financeiro
                                </h6>

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $pedido['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="atualizar_financeiro"
                                        value="1"
                                    >


                                    <div class="row g-3 align-items-end">


                                        <div class="col-12 col-md-5">

                                            <label class="form-label">
                                                Forma de pagamento
                                            </label>

                                            <select
                                                name="pagamento"
                                                class="form-select"
                                            >

                                                <option value="">
                                                    Selecione...
                                                </option>

                                                <option
                                                    value="Pix"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Pix') ? 'selected' : '' ?>
                                                >
                                                    📱 Pix
                                                </option>

                                                <option
                                                    value="Dinheiro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Dinheiro') ? 'selected' : '' ?>
                                                >
                                                    💵 Dinheiro
                                                </option>

                                                <option
                                                    value="Cartão de Crédito"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Cartão de Crédito') ? 'selected' : '' ?>
                                                >
                                                    💳 Cartão de Crédito
                                                </option>

                                                <option
                                                    value="Cartão de Débito"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Cartão de Débito') ? 'selected' : '' ?>
                                                >
                                                    💳 Cartão de Débito
                                                </option>

                                                <option
                                                    value="Outro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Outro') ? 'selected' : '' ?>
                                                >
                                                    💰 Outro
                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-12 col-md-4">

                                            <label class="form-label">
                                                Situação
                                            </label>

                                            <div class="form-check form-switch">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="pago"
                                                    value="1"
                                                    id="pago<?= $pedido['id'] ?>prep"
                                                    <?= !empty($pedido['pago']) ? 'checked' : '' ?>
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="pago<?= $pedido['id'] ?>prep"
                                                >

                                                    <?php if (!empty($pedido['pago'])): ?>

                                                        <span class="status-pagamento pago">
                                                            ✅ Pago
                                                        </span>

                                                        <?php if (!empty($pedido['data_pagamento'])): ?>

                                                            <span class="data-pagamento">

                                                                📅 Pago em:

                                                                <?= date(
                                                                    'd/m/Y H:i',
                                                                    strtotime($pedido['data_pagamento'])
                                                                ) ?>

                                                            </span>

                                                        <?php endif; ?>

                                                    <?php else: ?>

                                                        <span class="status-pagamento nao-pago">
                                                            ❌ Não pago
                                                        </span>

                                                    <?php endif; ?>

                                                </label>

                                            </div>

                                        </div>


                                        <div class="col-12 col-md-3">

                                            <button
                                                type="submit"
                                                class="btn btn-success w-100"
                                            >
                                                💾 Salvar
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="alert alert-info">
                    Nenhum pedido em preparo no momento.
                </div>

            <?php endif; ?>

        </div>


        <!-- =================================================
             SAIU PARA ENTREGA
        ================================================= -->

        <div
            class="tab-pane fade"
            id="entrega"
            role="tabpanel"
        >

            <?php if (count($pedidosSaiuEntrega) > 0): ?>

                <?php foreach ($pedidosSaiuEntrega as $pedido): ?>

                    <div
                        class="pedido-card card"
                        id="pedido-<?= $pedido['id'] ?>"
                    >

                        <div class="card-header bg-info text-white">

                            <div class="row align-items-center">

                                <div class="col">

                                    <h6 class="mb-0">

                                        <span class="badge bg-light text-dark">
                                            SAIU PARA ENTREGA
                                        </span>

                                        <strong>
                                            Pedido #<?= $pedido['id'] ?>
                                        </strong>

                                        <small>

                                            <?= date(
                                                'd/m/Y H:i',
                                                strtotime($pedido['data_pedido'])
                                            ) ?>

                                        </small>

                                    </h6>

                                </div>


                                <div class="col-auto no-print">

                                    <button
                                        class="btn btn-sm btn-light"
                                        onclick="imprimirPedido(<?= $pedido['id'] ?>)"
                                    >
                                        🖨️
                                    </button>


                                    <form
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirmarExclusao(<?= $pedido['id'] ?>)"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="excluir_pedido"
                                            value="1"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            🗑️ Excluir
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">


                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <p>
                                        <strong>👤 Cliente:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['nome'] ?? 'N/A'
                                        ) ?>
                                    </p>

                                    <p>
                                        <strong>📞 Telefone:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['telefone_cliente'] ?? 'N/A'
                                        ) ?>
                                    </p>

                                </div>


                                <div class="col-md-6">

                                    <p>

                                        <strong>💳 Pagamento:</strong>

                                        <span class="badge bg-secondary">

                                            <?= htmlspecialchars(
                                                $pedido['pagamento'] ?? 'Não especificado'
                                            ) ?>

                                        </span>

                                    </p>

                                </div>

                            </div>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    📍 Endereço de Entrega:
                                </strong>

                                <div class="produto-area mt-2">

                                    <p class="mb-1">

                                        <?= htmlspecialchars(
                                            $pedido['rua'] ?? ''
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $pedido['numero'] ?? ''
                                        ) ?>

                                        -

                                        <?= htmlspecialchars(
                                            $pedido['bairro'] ?? ''
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $pedido['cidade'] ?? ''
                                        ) ?>

                                    </p>


                                    <?php if (
                                        !empty($pedido['ponto_de_referencia'])
                                    ): ?>

                                        <p class="mb-0">

                                            <small>

                                                Referência:

                                                <?= htmlspecialchars(
                                                    $pedido['ponto_de_referencia']
                                                ) ?>

                                            </small>

                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    🛒 Produtos:
                                </strong>

                                <div class="produto-area mt-2">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $pedido['item'] ?? 'Sem descrição'
                                        )
                                    ) ?>

                                </div>

                            </div>


                            <hr>


                            <div class="row align-items-center">

                                <div class="col">

                                    <h5>

                                        💰 Total:

                                        <span class="text-success">

                                            R$

                                            <?= number_format(
                                                $pedido['valor'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>

                                        </span>

                                    </h5>

                                </div>


                                <div class="col-auto no-print">

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <select
                                            name="status"
                                            class="form-select form-select-sm d-inline-block w-auto"
                                        >

                                            <option value="">
                                                Mudar status...
                                            </option>

                                            <option value="concluído">
                                                ✅ Concluído
                                            </option>

                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary ms-2"
                                        >
                                            Atualizar
                                        </button>

                                    </form>

                                </div>

                            </div>


                            <!-- FINANCEIRO -->

                            <div class="finance-box no-print">

                                <h6>
                                    💳 Controle financeiro
                                </h6>

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $pedido['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="atualizar_financeiro"
                                        value="1"
                                    >


                                    <div class="row g-3 align-items-end">


                                        <div class="col-12 col-md-5">

                                            <label class="form-label">
                                                Forma de pagamento
                                            </label>

                                            <select
                                                name="pagamento"
                                                class="form-select"
                                            >

                                                <option value="">
                                                    Selecione...
                                                </option>

                                                <option
                                                    value="Pix"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Pix') ? 'selected' : '' ?>
                                                >
                                                    📱 Pix
                                                </option>

                                                <option
                                                    value="Dinheiro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Dinheiro') ? 'selected' : '' ?>
                                                >
                                                    💵 Dinheiro
                                                </option>

                                                <option
                                                    value="Cartão de Crédito"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Cartão de Crédito') ? 'selected' : '' ?>
                                                >
                                                    💳 Cartão de Crédito
                                                </option>

                                                <option
                                                    value="Cartão de Débito"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Cartão de Débito') ? 'selected' : '' ?>
                                                >
                                                    💳 Cartão de Débito
                                                </option>

                                                <option
                                                    value="Outro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Outro') ? 'selected' : '' ?>
                                                >
                                                    💰 Outro
                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-12 col-md-4">

                                            <label class="form-label">
                                                Situação
                                            </label>

                                            <div class="form-check form-switch">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="pago"
                                                    value="1"
                                                    id="pago<?= $pedido['id'] ?>entrega"
                                                    <?= !empty($pedido['pago']) ? 'checked' : '' ?>
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="pago<?= $pedido['id'] ?>entrega"
                                                >

                                                    <?php if (!empty($pedido['pago'])): ?>

                                                        <span class="status-pagamento pago">
                                                            ✅ Pago
                                                        </span>

                                                        <?php if (!empty($pedido['data_pagamento'])): ?>

                                                            <span class="data-pagamento">

                                                                📅 Pago em:

                                                                <?= date(
                                                                    'd/m/Y H:i',
                                                                    strtotime($pedido['data_pagamento'])
                                                                ) ?>

                                                            </span>

                                                        <?php endif; ?>

                                                    <?php else: ?>

                                                        <span class="status-pagamento nao-pago">
                                                            ❌ Não pago
                                                        </span>

                                                    <?php endif; ?>

                                                </label>

                                            </div>

                                        </div>


                                        <div class="col-12 col-md-3">

                                            <button
                                                type="submit"
                                                class="btn btn-success w-100"
                                            >
                                                💾 Salvar
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="alert alert-info">
                    Nenhum pedido saiu para entrega no momento.
                </div>

            <?php endif; ?>

        </div>


        <!-- =================================================
             CONCLUÍDOS
        ================================================= -->

        <div
            class="tab-pane fade"
            id="concluido"
            role="tabpanel"
        >

            <?php if (count($pedidosConcluidos) > 0): ?>

                <?php foreach ($pedidosConcluidos as $pedido): ?>

                    <div
                        class="pedido-card card"
                        id="pedido-<?= $pedido['id'] ?>"
                    >

                        <div class="card-header bg-success text-white">

                            <div class="row align-items-center">

                                <div class="col">

                                    <h6 class="mb-0">

                                        <span class="badge bg-light text-dark">
                                            CONCLUÍDO
                                        </span>

                                        <strong>
                                            Pedido #<?= $pedido['id'] ?>
                                        </strong>

                                        <small>

                                            <?= date(
                                                'd/m/Y H:i',
                                                strtotime($pedido['data_pedido'])
                                            ) ?>

                                        </small>

                                    </h6>

                                </div>


                                <div class="col-auto no-print">

                                    <button
                                        class="btn btn-sm btn-light"
                                        onclick="imprimirPedido(<?= $pedido['id'] ?>)"
                                    >
                                        🖨️
                                    </button>


                                    <form
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirmarExclusao(<?= $pedido['id'] ?>)"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pedido['id'] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="excluir_pedido"
                                            value="1"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            🗑️ Excluir
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>


                        <div class="card-body">


                            <div class="row mb-3">

                                <div class="col-md-6">

                                    <p>
                                        <strong>👤 Cliente:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['nome'] ?? 'N/A'
                                        ) ?>
                                    </p>

                                    <p>
                                        <strong>📞 Telefone:</strong>

                                        <?= htmlspecialchars(
                                            $pedido['telefone_cliente'] ?? 'N/A'
                                        ) ?>
                                    </p>

                                </div>


                                <div class="col-md-6">

                                    <p>

                                        <strong>💳 Pagamento:</strong>

                                        <span class="badge bg-secondary">

                                            <?= htmlspecialchars(
                                                $pedido['pagamento'] ?? 'Não especificado'
                                            ) ?>

                                        </span>

                                    </p>

                                </div>

                            </div>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    📍 Endereço de Entrega:
                                </strong>

                                <div class="produto-area mt-2">

                                    <p class="mb-1">

                                        <?= htmlspecialchars(
                                            $pedido['rua'] ?? ''
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $pedido['numero'] ?? ''
                                        ) ?>

                                        -

                                        <?= htmlspecialchars(
                                            $pedido['bairro'] ?? ''
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $pedido['cidade'] ?? ''
                                        ) ?>

                                    </p>


                                    <?php if (
                                        !empty($pedido['ponto_de_referencia'])
                                    ): ?>

                                        <p class="mb-0">

                                            <small>

                                                Referência:

                                                <?= htmlspecialchars(
                                                    $pedido['ponto_de_referencia']
                                                ) ?>

                                            </small>

                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <hr>


                            <div class="mb-3">

                                <strong>
                                    🛒 Produtos:
                                </strong>

                                <div class="produto-area mt-2">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $pedido['item'] ?? 'Sem descrição'
                                        )
                                    ) ?>

                                </div>

                            </div>


                            <hr>


                            <div class="row align-items-center">

                                <div class="col">

                                    <h5>

                                        💰 Total:

                                        <span class="text-success">

                                            R$

                                            <?= number_format(
                                                $pedido['valor'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>

                                        </span>

                                    </h5>

                                </div>

                            </div>


                            <!-- FINANCEIRO -->

                            <div class="finance-box no-print">

                                <h6>
                                    💳 Controle financeiro
                                </h6>


                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $pedido['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="atualizar_financeiro"
                                        value="1"
                                    >


                                    <div class="row g-3 align-items-end">


                                        <div class="col-12 col-md-5">

                                            <label class="form-label">
                                                Forma de pagamento
                                            </label>

                                            <select
                                                name="pagamento"
                                                class="form-select"
                                            >

                                                <option value="">
                                                    Selecione...
                                                </option>

                                                <option
                                                    value="Pix"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Pix') ? 'selected' : '' ?>
                                                >
                                                    📱 Pix
                                                </option>

                                                <option
                                                    value="Dinheiro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Dinheiro') ? 'selected' : '' ?>
                                                >
                                                    💵 Dinheiro
                                                </option>

                                                <option
                                                    value="Cartão de Crédito"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Cartão de Crédito') ? 'selected' : '' ?>
                                                >
                                                    💳 Cartão de Crédito
                                                </option>

                                                <option
                                                    value="Cartão de Débito"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Cartão de Débito') ? 'selected' : '' ?>
                                                >
                                                    💳 Cartão de Débito
                                                </option>

                                                <option
                                                    value="Outro"
                                                    <?= (($pedido['pagamento'] ?? '') === 'Outro') ? 'selected' : '' ?>
                                                >
                                                    💰 Outro
                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-12 col-md-4">

                                            <label class="form-label">
                                                Situação
                                            </label>

                                            <div class="form-check form-switch">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="pago"
                                                    value="1"
                                                    id="pago<?= $pedido['id'] ?>concluido"
                                                    <?= !empty($pedido['pago']) ? 'checked' : '' ?>
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="pago<?= $pedido['id'] ?>concluido"
                                                >

                                                    <?php if (!empty($pedido['pago'])): ?>

                                                        <span class="status-pagamento pago">
                                                            ✅ Pago
                                                        </span>

                                                        <?php if (!empty($pedido['data_pagamento'])): ?>

                                                            <span class="data-pagamento">

                                                                📅 Pago em:

                                                                <?= date(
                                                                    'd/m/Y H:i',
                                                                    strtotime($pedido['data_pagamento'])
                                                                ) ?>

                                                            </span>

                                                        <?php endif; ?>

                                                    <?php else: ?>

                                                        <span class="status-pagamento nao-pago">
                                                            ❌ Não pago
                                                        </span>

                                                    <?php endif; ?>

                                                </label>

                                            </div>

                                        </div>


                                        <div class="col-12 col-md-3">

                                            <button
                                                type="submit"
                                                class="btn btn-success w-100"
                                            >
                                                💾 Salvar
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="alert alert-info">
                    Nenhum pedido concluído ainda.
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>


/*
|--------------------------------------------------------------------------
| CONFIRMAR EXCLUSÃO
|--------------------------------------------------------------------------
*/

function confirmarExclusao(id) {

    return confirm(
        '⚠️ ATENÇÃO!\n\n' +
        'Tem certeza que deseja excluir o pedido #' +
        id +
        '?\n\n' +
        'Essa ação não poderá ser desfeita.'
    );

}


/*
|--------------------------------------------------------------------------
| IMPRIMIR PEDIDO
|--------------------------------------------------------------------------
*/

function imprimirPedido(pedidoId) {

    const pedido =
        document.getElementById(
            'pedido-' + pedidoId
        );

    if (!pedido) return;


    const conteudoOriginal =
        document.body.innerHTML;


    document.body.innerHTML =
        pedido.innerHTML;


    window.print();


    document.body.innerHTML =
        conteudoOriginal;


    location.reload();

}


/*
|--------------------------------------------------------------------------
| NOTIFICAÇÕES
|--------------------------------------------------------------------------
*/

let ultimoNumeroPedidos =
    <?= count($pedidosNovos) ?>;


/*
|--------------------------------------------------------------------------
| PEDIR PERMISSÃO
|--------------------------------------------------------------------------
*/

function pedirPermissaoNotificacao() {

    if (
        'Notification' in window &&
        Notification.permission === 'default'
    ) {

        Notification.requestPermission();

    }

}


/*
|--------------------------------------------------------------------------
| SOM
|--------------------------------------------------------------------------
*/

function tocarSomNotificacao() {

    try {

        const audioContext =
            new (
                window.AudioContext ||
                window.webkitAudioContext
            )();


        const oscillator =
            audioContext.createOscillator();


        const gainNode =
            audioContext.createGain();


        oscillator.connect(gainNode);

        gainNode.connect(
            audioContext.destination
        );


        oscillator.frequency.value = 1000;

        oscillator.type = 'sine';


        gainNode.gain.setValueAtTime(
            0.3,
            audioContext.currentTime
        );


        gainNode.gain.exponentialRampToValueAtTime(
            0.01,
            audioContext.currentTime + 0.5
        );


        oscillator.start(
            audioContext.currentTime
        );


        oscillator.stop(
            audioContext.currentTime + 0.5
        );


        setTimeout(() => {

            const osc2 =
                audioContext.createOscillator();


            const gain2 =
                audioContext.createGain();


            osc2.connect(gain2);

            gain2.connect(
                audioContext.destination
            );


            osc2.frequency.value = 1500;

            osc2.type = 'sine';


            gain2.gain.setValueAtTime(
                0.3,
                audioContext.currentTime
            );


            gain2.gain.exponentialRampToValueAtTime(
                0.01,
                audioContext.currentTime + 0.5
            );


            osc2.start(
                audioContext.currentTime
            );


            osc2.stop(
                audioContext.currentTime + 0.5
            );

        }, 300);

    } catch (erro) {

        console.log(
            'Não foi possível tocar o som:',
            erro
        );

    }

}


/*
|--------------------------------------------------------------------------
| NOTIFICAÇÃO DO NAVEGADOR
|--------------------------------------------------------------------------
*/

function mostrarNotificacao(
    titulo,
    opcoes = {}
) {

    if (
        'Notification' in window &&
        Notification.permission === 'granted'
    ) {

        new Notification(
            titulo,
            {

                icon: '🔔',

                badge: '🔔',

                ...opcoes

            }
        );

    }

}


/*
|--------------------------------------------------------------------------
| VERIFICAR NOVOS PEDIDOS
|--------------------------------------------------------------------------
*/

function verificarNovosPedidos() {

    fetch(
        'index.php?action=verificarNovosPedidos'
    )

        .then(response =>
            response.json()
        )

        .then(data => {

            const novosPedidos =
                parseInt(
                    data.novosPedidos || 0
                );


            if (
                novosPedidos >
                ultimoNumeroPedidos
            ) {

                const pedidosAdicionados =
                    novosPedidos -
                    ultimoNumeroPedidos;


                tocarSomNotificacao();


                mostrarNotificacao(

                    `🎉 ${pedidosAdicionados} NOVO${pedidosAdicionados > 1 ? 'S' : ''} PEDIDO${pedidosAdicionados > 1 ? 'S' : ''}!`,

                    {

                        body:
                            `Total de ${novosPedidos} pedido${novosPedidos > 1 ? 's' : ''} não processado${novosPedidos > 1 ? 's' : ''}`

                    }

                );


                const badge =
                    document.getElementById(
                        'novosPedidosBadge'
                    );


                if (badge) {

                    badge.style.animation =
                        'none';


                    setTimeout(() => {

                        badge.textContent =
                            novosPedidos;


                        badge.style.animation =
                            'pulse 1s';

                    }, 10);

                }


                setTimeout(() => {

                    location.reload();

                }, 2000);

            }


            ultimoNumeroPedidos =
                novosPedidos;

        })

        .catch(err => {

            console.log(
                'Erro ao verificar pedidos:',
                err
            );

        });

}


/*
|--------------------------------------------------------------------------
| INICIALIZAÇÃO
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    () => {

        pedirPermissaoNotificacao();


        setInterval(
            verificarNovosPedidos,
            10000
        );

    }
);

</script>


</body>

</html>