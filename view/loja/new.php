<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 26/02/2018
 * Time: 14:34
 */

//print_r($dados);
//print_r(var_dump($dados['dt_pedido']));
//$today = date('Y-m-d',time());
//print_r(var_dump($today));
?>
<script>
    $._keypress(function (event) {
        if (event.keyCode == 10 || event.keyCode == 13) {
            event.preventDefault();
        }
    });
</script>

<h1>Nova solicitação</h1>
<hr/>
<div class="container">
    <form action="" method="post" id="flexform">
        <div class="col col-lg-12">
            <div class="row">
                <div class="col col-md-5">
                    <h6>Informe o número do pedido e clique em Buscar</h6>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-5 input-group">
                    <input class="form-control" type="number" name="nr_pedido" id="nr_pedido"
                           placeholder="Nr. pedido" required>
                    <span class="input-group-btn">
                <input type="submit" class="btn btn-success" value="Buscar"/>
                </span>
                </div>
            </div>
        </div>
    </form>
    <?php if (isset($dados) && count($dados) > 1) { ?>
    <form action="<?php echo $action; ?>" method="post" id="formflex">
        <hr/>
        <input type="hidden" name="cd_filial" value="<?php echo $dados['cd_filial']; ?>">
        <input type="hidden" name="nr_pedido" value="<?php echo $dados['nr_pedido']; ?>">
        <input type="hidden" name="cd_supefili" value="<?php echo $dados['cd_supefili']; ?>">
        <input type="hidden" name="nm_supefili" value="<?php echo $dados['nm_supefili']; ?>">
        <div class="container">
            <h3>Pedido número <?php echo $dados['nr_pedido']; ?></h3>
            <hr/>
            <div class="row">
                <div class="col col-lg-2">
                    <span>Cliente</span>
                </div>
                <div class="col col-lg-1">
                    <input type="hidden" class="form-control" name="cd_cliente" id="cd_cliente"
                           value="<?php echo $dados['cd_cliente']; ?>">
                    <?php echo $dados['cd_cliente']; ?>
                </div>
                <div class="col col-lg-6">
                    <input type="hidden" class="form-control" name="nm_cliente" id="nm_cliente"
                           value="<?php echo $dados['nm_cliente']; ?>">
                    <?php echo $dados['nm_cliente']; ?>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col col-lg-2">
                    <span>Tipo de cobrança</span>
                </div>
                <div class="col col-lg-1">
                    <input type="hidden" class="form-control" name="cd_tipocobr" id="cd_tipocobr"
                           value="<?php echo $dados['cd_tipocobr']; ?>">
                    <?php echo $dados['cd_tipocobr']; ?>
                </div>
                <div class="col col-lg-3">
                    <input type="hidden" class="form-control" name="nm_tipocobr" id="nm_tipocobr"
                           value="<?php echo $dados['nm_tipocobr']; ?>">
                    <?php echo $dados['nm_tipocobr']; ?>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col col-lg-2">
                    <span>Condição de pagamento</span>
                </div>
                <div class="col col-lg-1">
                    <input type="hidden" class="form-control" name="cd_condpgto" id="cd_condpgto"
                           value="<?php echo $dados['cd_condpgto']; ?>">
                    <?php echo $dados['cd_condpgto']; ?>
                </div>
                <div class="col col-lg-3">
                    <input type="hidden" class="form-control" name="nm_condpgto" id="nm_condpgto"
                           value="<?php echo $dados['nm_condpgto']; ?>">
                    <?php echo $dados['nm_condpgto']; ?>
                </div>
            </div>
            <hr/>
            <h4>Produtos</h4>
            <table class="table table-responsive-md">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Tabela 2</th>
                    <th class="tabprec">Tab preço</th>
                    <th>Valor</th>
                    <th>Valor c/ desconto</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dados['produtos'] as $key => $value) { ?>
                    <tr>
                        <td>
                            <input type="hidden" class="form-control" name="cd_produto[<?php echo $key; ?>]"
                                   id="cd_produto<?php echo $key; ?>"
                                   value="<?php echo $value['cd_produto']; ?>">
                            <?php echo $value['cd_produto']; ?>
                        </td>
                        <td>
                            <input type="hidden" class="form-control" name="nm_produto[<?php echo $key; ?>]"
                                   id="nm_produto<?php echo $key; ?>"
                                   value="<?php echo $value['nm_produto']; ?>">
                            <?php echo substr($value['nm_produto'], 0, 25) . '...'; ?>
                        </td>
                        <td>
                            <input type="hidden" class="form-control" name="fl_foralinha[<?php echo $key; ?>]"
                                   id="fl_foralinha<?php echo $key; ?>"
                                   value="<?php echo $value['fl_foralinha']; ?>">
                            <?php echo $value['fl_foralinha']; ?>
                        </td>
                        <td class="tabprec">
                            <input type="hidden" class="form-control" name="nr_tabpreco[<?php echo $key; ?>]"
                                   id="nr_tabpreco<?php echo $key; ?>"
                                   value="<?php echo $value['nr_tabpreco']; ?>">
                            <?php echo $value['nr_tabpreco']; ?>
                        </td>
                        <td>
                            <input type="hidden" class="form-control" name="vl_total[<?php echo $key; ?>]"
                                   id="vl_total<?php echo $key; ?>"
                                   value="<?php echo $value['vl_total']; ?>">
                            <?php echo 'R$ ' . number_format($value['vl_total'], 2, ',', '.'); ?>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="vl_desc[<?php echo $key; ?>]"
                                   id="vl_desc<?php echo $key; ?>" required>
                        </td>
                    </tr>
                    <script>
                        $('#vl_desc<?php echo $key; ?>').maskMoney({
                            prefix: 'R$ ',
                            allowNegative: false,
                            thousands: '.',
                            decimal: ',',
                            affixesStay: false,
                            allowEmpty: true
                        });

                        //                       $(document).ready(function () {
                        //                           //set initial state.
                        //
                        //                           $('#nodesc<?php echo $key; ?>').change(function () {
                        //                               if (this.checked) {
                        //                                   var returnVal = confirm("Voce tem certeza?");
                        //                                   $(this).prop("checked", returnVal);
                        //                                   $('#vl_desc<?php echo $key; ?>').prop('readonly', true);
                        //                                   $('#vl_desc<?php echo $key; ?>').prop('value', '0');
                        //                                   $('#vl_desc<?php echo $key; ?>').prop('required', false);
                        //                               }
                        //                               if (!this.checked) {
                        //                                   var returnVal = confirm("Voce tem certeza?");
                        //                                   $(this).prop("checked", returnVal);
                        //                                   $('#vl_desc<?php echo $key; ?>').prop('readonly', false);
                        //                                   $('#vl_desc<?php echo $key; ?>').prop('required', true);
                        //                               }
                        //
                        //                           });
                        //                       });
                    </script>

                <?php } ?>

                </tbody>
            </table>


            <div class="row">
                <div class="col col-lg-12">
                    <span>Observações loja</span>
                    <textarea class="form-control" name="observacao"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col col-lg-5">
                    <span>Motivo</span>
                    <select class="form-control" name="motivo">
                        <option value="">Selecione um motivo</option>
                        <?php foreach ($listMotivos as $motivo){ ?>
                            <option value="<?php echo $motivo['cd_motivo'] ?>"><?php echo $motivo['descricao']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col col-lg-5">
                    <input type="submit" class="btn btn-success" value="Enviar">
                    <a href="home.php?action=novo" class="btn btn-warning">Cancelar</a>
                </div>
            </div>

        </div>
    </form>
</div>
<?php } ?>
