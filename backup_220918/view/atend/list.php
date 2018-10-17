<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 26/02/2018
 * Time: 14:34
 */
?>

<h1>Lista de solicitações</h1>
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
        <a class="nav-link <?php echo $status == 3 ? 'active' : ''; ?>" href="home.php?filter=respondido&cdatend=<?php echo $_SESSION['cd_usuario']; ?>">Respondido</a>
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
                <td><?php echo date('d/m/Y H:m', strtotime($value['data_solicitacao'])); ?></td>
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
