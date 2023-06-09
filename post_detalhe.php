<!DOCTYPE html>
<?php
    require_once 'includes/funcoes.php';
    require_once 'core/conexao_mysql.php';
    require_once 'core/sql.php';
    require_once 'core/mysql.php';

    foreach($_GET as $indice => $dado) {
        $$indice = limparDados($dado);
    }

    $posts = buscar(
        'publicacao',
        [
            'id_publicacao',
            'titulo',
            'data_publicacao',
            'texto',
            'foto_nome_publi',
            '(select nome
                from pessoa
                where pessoa.id_pessoa = publicacao.id_pessoa) as nome',
            '(select id_pessoa
                from pessoa
                where pessoa.id_pessoa = publicacao.id_pessoa) as id_pessoa',
            '(select foto_nome_pessoa
                from pessoa
                where id_pessoa = publicacao.id_pessoa) as foto_nome_pessoa'
        ],
        [
            ['id_publicacao', '=', $post]
        ]
    );
    $post = $posts[0];
    $data_post = date_create($post['data_publicacao']);
    $data_post = date_format($data_post, 'd/m/Y');
    $hora_post = date_create($post['data_publicacao']);
    $hora_post = date_format($hora_post, 'H:i');
    $fotos = explode(';',$post['foto_nome_publi']);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/style-navegador.css">
        <link rel="stylesheet" href="style/style-post-detalhe.css">
        <link rel="stylesheet" href="style/style-mq.css">
        <?php
            include('includes/settings.php');
            if(!isset($_SESSION['login'])){
                header("Location: login.php");
                exit();
            }
        ?>
        <title>COMECOM | <?php echo $post['titulo']?></title>
    </head>
    <body>
        <?php include 'includes/navigator.php' ?>
        <div>
            <div>
                <div>
                    <div class="container2">
                        <div class="container2-1">
                            <div class="comecom-avatar">
                            <?php if ($post['foto_nome_pessoa'] == null) : ?>
                                <a href="user.php?id_pessoa=<?php echo $post['id_pessoa']?>"><img class="user-img" src="media/icons/solid/user2.svg" alt="login">
                            <?php else : ?>
                                <a href="user.php?id_pessoa=<?php echo $post['id_pessoa']?>"><img src="upload/user/<?php echo $post['foto_nome_pessoa']?>" alt="<?php echo $post['foto_nome_pessoa']?>" style="border-radius: 50%;">
                            <?php endif; ?>
                                <h4><span><?php echo $post['nome'] ?></a> • Postado em: <?php echo $data_post . ' às ' . $hora_post?></span></h4>
                            </div>
                        </div>
                        <div class="post-title"><h3><strong><?php echo $post['titulo']?></strong></h3></div>
                        <div class="post-texto"><h4><?php echo html_entity_decode($post['texto']) ?></h4></div>
                        <div class="post-img" align="center">
                            <?php foreach($fotos as $foto) : ?>
                                <?php if ($foto != '') : ?>
                                    <img src='<?php echo"upload/post/".$foto; ?>' style="width: 45%">
                                <?php endif; ?>
                            <?php endforeach ?>
                        </div>
                        <?php if(($post['id_pessoa'] == $_SESSION['login']['pessoa']['id_pessoa']) || ($_SESSION['login']['pessoa']['adm'] == 1)) : ?>
                        <div class="delete-post" id="delete">
                            <div class="delete"><a href="core/post_repositorio.php?acao=delete&id_publicacao=<?php echo $post['id_publicacao']?>&id_pessoa=<?php echo $post['id_pessoa']?>"><span>Excluir postagem</span><img src="media/icons/line/trash.svg" alt="excluir postagem"></a></div>
                        </div>
                        <?php endif;?>
                    </div>
                    
                </div>
            </div>
        </div>
        <?php include 'includes/footer.php' ?>
    </body>
</html>