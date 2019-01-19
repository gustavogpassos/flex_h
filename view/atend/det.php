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

//print_r($_SESSION);

if (isset($dados) && count($dados) > 1) {
    ?>

    <h1>Flex <?php echo $dados['cd_flex'] . '  Filial ' . $dados['cd_filial']; ?></h1>
    <h6>Abertura <?php echo date('d/m/Y H:i', strtotime($dados['data_solicitacao'])); ?></h6>
    <?php //echo 'Responsável: '.$dados['nome']; ?>
    <hr/>
    <div class="container" style="padding: 0">
        <a href="home.php" class="btn btn-primary">Voltar</a>

        <hr/>
        <div class="container" style="padding: 0">
            <h4>Pedido número <?php echo $dados['nr_pedido']; ?></h4>

            <hr/>
            <form action="home.php?action=gravar&idflex=<?php echo $dados['cd_flex']; ?>" method="post">
                <!-- Inputs necessários para gerar a senha -->
                <input type="hidden" name="cd_filial" id="cd_filial" value="<?php echo $dados['cd_filial']; ?>"/>
                <input type="hidden" name="nr_pedido" id="nr_pedido" value="<?php echo $dados['nr_pedido']; ?>"/>
                <input type="hidden" name="cd_flex" id="cd_flex" value="<?php echo $dados['cd_flex']; ?>"/>
                <div class="row">
                    <div class="col col-lg-2 hide-mob">
                        <span>Cliente</span>
                    </div>
                    <div class="col col-lg-1 show-mob-1">
                        <?php echo $dados['cd_cliente']; ?>
                        <input type="hidden" name="cd_cliente" id="cd_cliente"
                               value="<?php echo $dados['cd_cliente']; ?>"/>
                    </div>
                    <div class="col col-lg-6 show-mob-2">
                        <?php
                        echo $dados['nm_cliente'];
                        if (isset($dados['flex_cliente']) && is_array($dados['flex_cliente']) && count($dados['flex_cliente'])>0) {
                            //print_r($dados['flex_cliente']);
                            echo "&nbsp;<br/> Esse cliente possui solicitações nos ultimos 30 dias.<br/> ";
                            foreach ($dados['flex_cliente'] as $value) {
                                echo '<a target="_blank" href="home.php?action=detalhe&idflex=' . $value['cd_flex'] . '">' . $value['cd_flex'] . '</a> &NonBreakingSpace;';
                            }
                        }
                        ?>

                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col col-lg-2 hide-mob">
                        <span>Tipo de cobrança</span>
                    </div>
                    <div class="col col-lg-1 show-mob-1">
                        <?php echo $dados['cd_tipocobr']; ?>
                        <input type="hidden" name="cd_tipocobr" id="cd_tipocobr"
                               value="<?php echo $dados['cd_tipocobr']; ?>"/>
                    </div>
                    <div class="col col-lg-3 show-mob-2">
                        <?php echo $dados['nm_tipocobr']; ?>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col col-lg-2 hide-mob">
                        <span>Condição de pagamento</span>
                    </div>
                    <div class="col col-lg-1 show-mob-1">
                        <?php echo $dados['cd_condpgto']; ?>
                        <input type="hidden" name="cd_condpgto" id="cd_condpgto"
                               value="<?php echo $dados['cd_condpgto']; ?>"/>
                    </div>
                    <div class="col col-lg-3 show-mob-2">
                        <?php echo $dados['nm_condpgto']; ?>
                    </div>
                </div>
                <hr/>
                <h4>Produtos</h4>
                <table class="table table-responsive-md" id="tbprod">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>T2</th>
                        <th class="tabprec">Tab preço</th>
                        <th>Valor</th>
                        <th>Valor c/ desconto</th>
                        <th>% desconto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($dados['produtos'] as $key => $value) { ?>
                        <tr>
                            <td>
                                <?php echo $value['cd_produto']; ?>
                                <input type="hidden" name="cd_produto[<?php echo $key; ?>]"
                                       id="cd_produto<?php echo $key; ?>" value="<?php echo $value['cd_produto']; ?>">
                            </td>
                            <td>
                                <?php echo substr($value['nm_produto'], 0, 22) . '...'; ?>
                            </td>
                            <td>
                                <?php echo $value['fl_foralinha']; ?>
                            </td>
                            <td class="tabprec">
                                <?php echo $value['nr_tab_oferta']; ?>
                                <input type="hidden" name="nr_tabpreco[<?php echo $key; ?>]"
                                       id="nr_tabpreco<?php echo $key; ?>"
                                       value="<?php echo $value['nr_tab_oferta']; ?>">
                            </td>
                            <td>
                                <?php echo 'R$ ' . number_format($value['vl_minimo'], 2, ',', '.'); ?>
                            </td>
                            <td>
                                <?php echo 'R$ ' . number_format($value['vl_desconto'], 2, ',', '.'); ?>
                                <input type="hidden" name="vl_desc[<?php echo $key; ?>]" id="vl_desc<?php echo $key; ?>"
                                       value="<?php echo $value['vl_desconto']; ?>">
                            </td>
                            <td>
                                <?php echo $value['perc_desc'] . ' %'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span>Senha</span></td>
                            <td colspan="3"><input name="nr_senha[<?php echo $key; ?>]" id="nr_senha<?php echo $key; ?>"
                                                   class="form-control" readonly=""
                                                   value="<?php echo (stristr($value['nr_senha'], 'Negado')) ? "Negado" : number_format($value['nr_senha'], 2, '.', ''); ?>"
                                                   required=""></td>
                            <td class="tabprec"></td>
                            <?php if (($dados['status'] == 2  && $_SESSION['tp_usuario']=='regional' && $_SESSION['limite']!=true)||($_SESSION['tp_usuario']!='regional' && $dados['status']==2)) { ?>
                                <td>
                                    <button type="button" class="btn btn-success"
                                            onclick="gerasenha(<?php echo $key; ?>)">
                                        Gerar senha
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning"
                                            onclick="negasenha(<?php echo $key; ?>)">
                                        Negar
                                    </button>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <span>Observações loja</span><br/>
                        <textarea class="form-control" name="obsloja"
                                  readonly><?php echo $dados['obsloja']; ?></textarea>
                    </div>
                </div>
                <?php if ($dados['status'] != 1) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <span>Observações matriz</span><br/>
                            <textarea class="form-control" name="obsmatriz" required
                                      placeholder="Observações adicionais" <?php echo ($dados['status'] == 3) ? "readonly" : ""; ?>><?php echo $dados['obsmatriz']; ?></textarea>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-4">
                        <span>Motivo</span><br/>
                        <input class="form-control" name="motivo" value="<?php echo isset($dados['motivo'])?$dados['motivo']:''; ?>" readonly>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <?php if (($dados['status'] == 1  && $_SESSION['tp_usuario']=='regional' && $_SESSION['limite']!=true)||($_SESSION['tp_usuario']!='regional' && $dados['status']==1)) { ?>
                            <a href="home.php?action=assumir&idflex=<?php echo $dados['cd_flex']; ?>"
                               class="btn btn-primary">Assumir</a>
                        <?php } else if ($dados['status'] != 3) { ?>
                            <input type="submit" class="btn btn-primary" value="Gravar dados">
                        <?php } ?>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#tbprod').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                }
            });
        })
    </script>
<?php } ?>
