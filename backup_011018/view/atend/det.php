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

    <h1>Solicitação nr <?php echo $dados['cd_flex'] . ' - Filial ' . $dados['cd_filial']; ?></h1>
    <hr/>
    <div class="container">
        <a href="home.php" class="btn btn-primary">Voltar</a>

        <hr/>
        <div class="container">
            <h4>Pedido número <?php echo $dados['nr_pedido']; ?></h4>
            <hr/>
            <form action="home.php?action=gravar&idflex=<?php echo $dados['cd_flex']; ?>" method="post">
                <!-- Inputs necessários para gerar a senha -->
                <input type="hidden" name="cd_filial" id="cd_filial" value="<?php echo $dados['cd_filial']; ?>"/>
                <input type="hidden" name="nr_pedido" id="nr_pedido" value="<?php echo $dados['nr_pedido']; ?>"/>
                <input type="hidden" name="cd_flex" id="cd_flex" value="<?php echo $dados['cd_flex']; ?>"/>
                <div class="row">
                    <div class="col col-lg-2">
                        <span>Cliente</span>
                    </div>
                    <div class="col col-lg-1">
                        <?php echo $dados['cd_cliente']; ?>
                        <input type="hidden" name="cd_cliente" id="cd_cliente"
                               value="<?php echo $dados['cd_cliente']; ?>"/>
                    </div>
                    <div class="col col-lg-6">
                        <?php echo $dados['nm_cliente']; ?>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col col-lg-2">
                        <span>Tipo de cobrança</span>
                    </div>
                    <div class="col col-lg-1">
                        <?php echo $dados['cd_tipocobr']; ?>
                        <input type="hidden" name="cd_tipocobr" id="cd_tipocobr"
                               value="<?php echo $dados['cd_tipocobr']; ?>"/>
                    </div>
                    <div class="col col-lg-3">
                        <?php echo $dados['nm_tipocobr']; ?>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col col-lg-2">
                        <span>Condição de pagamento</span>
                    </div>
                    <div class="col col-lg-1">
                        <?php echo $dados['cd_condpgto']; ?>
                        <input type="hidden" name="cd_condpgto" id="cd_condpgto"
                               value="<?php echo $dados['cd_condpgto']; ?>"/>
                    </div>
                    <div class="col col-lg-3">
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
                                <?php echo $value['perc_desc'].' %'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span>Senha</span></td>
                            <td><input name="nr_senha[<?php echo $key; ?>]" id="nr_senha<?php echo $key; ?>"
                                       class="form-control" readonly=""
                                       value="<?php echo (stristr($value['nr_senha'], 'Negado'))?"Negado":number_format($value['nr_senha'],2,'.',''); ?>" required=""></td>
                            <td class="tabprec"></td>
                            <?php if ($dados['status'] == 2 && $_SESSION['tp_usuario'] != 'regional') { ?>
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
                            <td></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6">
                            <span>Observações loja</span><br/>
                            <textarea class="form-control" name="obsloja"
                                      readonly><?php echo $dados['obsloja']; ?></textarea>
                        </td>
                    </tr>
                    <?php if ($dados['status'] != 1) { ?>
                        <tr>
                            <td colspan="6">
                                <span>Observações matriz</span><br/>
                                <textarea class="form-control" name="obsmatriz"
                                          placeholder="Observações adicionais" <?php echo ($dados['status']==3)?"readonly":""; ?>><?php echo $dados['obsmatriz']; ?></textarea>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <?php if ($dados['status'] == 1 && $_SESSION['tp_usuario'] != 'regional') { ?>
                                <a href="home.php?action=assumir&idflex=<?php echo $dados['cd_flex']; ?>"
                                   class="btn btn-primary">Assumir</a>
                            <?php } else if ($dados['status'] != 3 && $_SESSION['tp_usuario'] != 'regional') { ?>
                                <input type="submit" class="btn btn-primary" value="Gravar dados">
                            <?php } ?>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </form>
        </div>
    </div>
<?php } ?>
