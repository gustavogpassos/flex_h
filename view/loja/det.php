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

    <h1>Solicitação nr <?php echo $dados['cd_flex']; ?></h1>
    <hr/>
    <div class="container">
        <a href="home.php" class="btn btn-primary">Voltar</a>

        <hr/>
        <div class="container">
            <h4>Pedido número <?php echo $dados['nr_pedido']; ?></h4>
            <hr/>
            <div class="row">
                <div class="col col-lg-2">
                    <span>Cliente</span>
                </div>
                <div class="col col-lg-1">
                    <?php echo $dados['cd_cliente']; ?>
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
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($dados['produtos'] as $key => $value) { ?>
                    <tr>
                        <td>
                            <?php echo $value['cd_produto']; ?>

                        </td>
                        <td>
                            <?php echo substr($value['nm_produto'], 0, 25) . '...'; ?>
                        </td>
                        <td>
                            <?php echo $value['fl_foralinha']; ?>
                        </td>
                        <td class="tabprec">
                            <?php echo $value['nr_tab_oferta']; ?>
                        </td>
                        <td>
                            <?php echo 'R$ ' . number_format($value['vl_minimo'], 2, ',', '.'); ?>
                        </td>
                        <td>
                            <?php echo 'R$ ' . number_format($value['vl_desconto'], 2, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><span>Senha</span></td>
                        <td><input name="nr_senha" class="form-control" disabled="true"
                                   value="<?php echo (stristr($value['nr_senha'], 'Negado')) ? "Negado" : number_format($value['nr_senha'], 2, '.', ''); ?>">
                        </td>
                        <td></td>
                        <td class="tabprec"></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <span>Observações loja</span><br/>
                    <textarea class="form-control"
                              name="obsloja" <?php echo ($dados['status'] > 0) ? "readonly" : ""; ?>><?php echo $dados['obsloja']; ?></textarea>
                </div>
            </div>
            <?php if (isset($dados['obsmatriz'])) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <span>Observações matriz</span><br/>
                        <textarea class="form-control" name="obsmatriz"
                                  placeholder="Observações adicionais"
                                  readonly><?php echo $dados['obsmatriz']; ?></textarea>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-5">
                    <span>Motivo</span><br/>
                    <input class="form-control" name="motivo"
                           value="<?php echo isset($dados['motivo']) ? $dados['motivo'] : ''; ?>" readonly>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
