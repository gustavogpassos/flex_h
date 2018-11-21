<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 26/02/2018
 * Time: 14:34
 */
?>
<div class="row">
    <?php if ($_SESSION['tp_usuario'] != 'regional') { ?>
        <div class="col col-md-6">
            <h3 style="position: absolute; bottom: 10%">Lista de solicitações</h3>
        </div>
        <?php if (isset($vlFlex)) { ?>
            <div class="col col-sm-6 vl-flex">
                <h5>Valores diários</h5>
                <table class="table">
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col col-sm-2">
                                    <div class="row">&nbsp;</div>
                                    <div class="row">Limite</div>
                                    <div class="row">Utilizado</div>
                                    <div class="row">Saldo</div>
                                </div>
                            </div>
                        </td>
                        <?php foreach ($vlFlex as $tmp) { ?>
                            <td>
                                <div class="row">
                                    <div class="col col-sm-2">
                                        <div class="row"><b>Regional&nbsp;<?php echo $tmp['regional'] ?></b></div>
                                        <div class="row">
                                            R$&nbsp;<?php echo number_format($tmp['limite'], 2, ',', ''); ?></div>
                                        <div class="row">
                                            R$&nbsp;<?php echo number_format($tmp['vl_flex'], 2, ',', ''); ?></div>
                                        <div class="row" style="color: <?php echo ($vlFlex[0]['limite']-$vlFlex[0]['vl_flex']<0)?'red':'' ?>">
                                            R$&nbsp;<?php echo number_format($tmp['limite']-$tmp['vl_flex'], 2, ',', ''); ?></div>
                                    </div>
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
                </table>
            </div
        <?php } ?>
    <?php } else { ?>
        <div class="col col-md-8">
            <h3 style="position: absolute; bottom: 10%">Lista de solicitações</h3>
        </div>
        <?php if (isset($vlFlex)) { ?>
            <div class="col col-sm-4 vl-flex">
                <h5>Valores diários</h5>
                <table class="table">
                    <thead>
                    <th></th>
                    <th>Regional&nbsp;<?php echo $vlFlex[0]['regional'] ?></th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Limite</td>
                        <td>R$&nbsp;<?php echo number_format($vlFlex[0]['limite'], 2, ',', ''); ?></td>
                    </tr>
                    <tr>
                        <td>Utilizado</td>
                        <td>R$&nbsp;<?php echo number_format($vlFlex[0]['vl_flex'], 2, ',', '') ?></td>
                    </tr>
                    <tr>
                        <td>Saldo</td>
                        <td style="color: <?php echo ($vlFlex[0]['limite']-$vlFlex[0]['vl_flex']<0)?'red':'' ?>">R$&nbsp;<?php echo number_format($vlFlex[0]['limite']-$vlFlex[0]['vl_flex'], 2, ',', '') ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<hr/>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php echo $status == 1 ? 'active' : ''; ?>" href="home.php">Novas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status == 2 ? 'active' : ''; ?>"
           href="home.php?filter=atendimento&cdatend=<?php echo $_SESSION['cd_usuario']; ?>">Em atendimento</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status == 3 ? 'active' : ''; ?>"
           href="home.php?filter=respondido&cdatend=<?php echo $_SESSION['cd_usuario']; ?>">Respondido</a>
    </li>
</ul><br/>
<table class="table" id="flexlist" style="width: 100%">
    <thead>
    <th>Nº</th>
    <th>Solicitante</th>
    <th>Abertura</th>
    <th>Status</th>
    <th></th>
    </thead>
    <tbody>
    <?php if (isset($listaFlex) && count($listaFlex) > 0) {
        foreach ($listaFlex as $key => $value) { ?>
            <tr>
                <td><?php echo $value['cd_flex']; ?></td>
                <td>Filial <?php echo $value['cd_filial']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($value['data_solicitacao'])); ?></td>
                <td><?php echo ($value['status'] == 2) ? 'Em atendimento' : ($value['status'] == 3) ? 'Respondido' : 'Novo'; ?></td>
                <td>
                    <a href="home.php?action=detalhe&idflex=<?php echo $value['cd_flex']; ?>"
                       class="btn btn-outline-primary">Detalhes...</a>
                </td>
            </tr>
            <?php
        }
    } ?>

    </tbody>
</table>
<script>
    /*setTimeout(function () {
        window.location.reload(1);
    }, 20000);*/
</script>
