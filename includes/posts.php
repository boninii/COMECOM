<div>
    <h2 align="center" style="color: #34345c;"><div class=""><?php include 'busca.php'; ?></div></h2>
    <hr style="width: 25%; margin: auto; margin-top: 20px; margin-bottom: 30px;">   
    <?php
        require_once 'funcoes.php';
        require_once '../core/conexao_mysql.php';
        require_once '../core/sql.php';
        require_once '../core/mysql.php';

        foreach($_GET as $indice => $dado) {
            $$indice = limparDados($dado);
        }

        $data_atual = date('Y-m-d H:i:s');

        $criterio = [
            ['data_publicacao', '<=', $data_atual]
        ];

        if(!empty($busca)) {
            $criterio[] = [
                'AND',
                'titulo',
                'like', 
                "%{$busca}%"
            ];
        }

        $posts = buscar (
            'publicacao',
            [
                'titulo',
                'data_publicacao',
                'id_publicacao',
                'foto_nome_publi',
                '(select id_pessoa
                    from pessoa
                    where id_pessoa = publicacao.id_pessoa) as id_pessoa',
                '(select nome
                    from pessoa
                    where id_pessoa = publicacao.id_pessoa) as nome',
                '(select foto_nome_pessoa
                    from pessoa
                    where id_pessoa = publicacao.id_pessoa) as foto_nome_pessoa'
            ],
            $criterio,
            'data_publicacao DESC'
        );
    ?>

    <div>
        <div>
            <?php
                foreach($posts as $post) :
                    $data = date_create($post['data_publicacao']);
                    $data = date_format($data, 'd/m/Y');
                    $hora = date_create($post['data_publicacao']);
                    $hora = date_format($hora, 'H:i');                            
                    $fotos = explode(';',$post['foto_nome_publi']);                                 
            ?>
            <div class="container2" id="post<?php echo $post['id_publicacao']?>">
                <div class="container2-1">
                    <div class="comecom-avatar">
                        <?php if ($post['foto_nome_pessoa'] == null) : ?>
                            <a href="../user.php?id_pessoa=<?php echo $post['id_pessoa']?>"><img class="user-img" src="<?php echo url($url, $com, $home) ?>media/icons/solid/user2.svg" alt="login">
                        <?php else : ?>
                            <img src="../upload/user/<?php echo $post['foto_nome_pessoa']?>" alt="<?php echo $post['foto_nome_pessoa']?>">
                        <?php endif; ?>
                        <h4><span><?php echo $post['nome']?></a> • Postado em: <?php echo $data . ' às ' . $hora?></span></h4>
                    </div>
                </div>
                <div class="post-title"><h3><?php echo $post['titulo']?></h3></div>
                <div class="post-img" align="center">
                    <?php foreach($fotos as $foto) : ?>
                        <?php if ($foto != '') : ?>
                            <img src='<?php echo"../upload/post/".$foto; ?>' style="width: 45%">
                        <?php endif; ?>
                    <?php endforeach ?>
                </div>
                <div class="container2-3">
                    <div class="comecom-denunciar">
                        <a href="../post_detalhe.php?post=<?php echo $post['id_publicacao']?>">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" fill="currentColor" viewBox="0 0 24 24">
                                    <path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" d="M3.6 7.2a3.6 3.6 0 0 1 3.6-3.6h12a1.2 1.2 0 0 1 .96 1.92L17.1 9.6l3.06 4.08a1.201 1.201 0 0 1-.96 1.92h-12A1.2 1.2 0 0 0 6 16.8v3.6a1.2 1.2 0 0 1-2.4 0V7.2Z" clip-rule="evenodd"/>
                                </svg>
                                Ver detalhes
                            </p>
                        </a>                
                        <a href="#" onclick="denuncia()">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13.2 3.6a1.2 1.2 0 1 0 0 2.4h3.103l-7.551 7.552a1.2 1.2 0 1 0 1.696 1.696L18 7.697V10.8a1.2 1.2 0 1 0 2.4 0v-6a1.2 1.2 0 0 0-1.2-1.2h-6Z"/>
                                    <path d="M6 6a2.4 2.4 0 0 0-2.4 2.4V18A2.4 2.4 0 0 0 6 20.4h9.6A2.4 2.4 0 0 0 18 18v-3.6a1.2 1.2 0 1 0-2.4 0V18H6V8.4h3.6a1.2 1.2 0 1 0 0-2.4H6Z"/>
                                </svg>
                                Denunciar postagem
                            </p>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function denuncia() {
            var rand = Math.floor(Math.random() * 10000000) + 100000;
            alert('Denuncia realizada com sucesso!\nNossa equipe agradece e ja está tomando as devidas providências.\n\nID da denúncia: #'+rand);
        }
    </script>
</div>      