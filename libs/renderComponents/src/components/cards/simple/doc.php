
<?php require_once 'libs/renderComponents/doc.php'; ?>

<?php $component->render('cards/simple/start', ['title' => 'Contratações Diretas'], true) ?>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum, aspernatur! Autem deserunt maxime soluta, suscipit vitae est delectus eius eveniet. Illum in quae consectetur suscipit sunt maxime reprehenderit quo blanditiis!</p>
<?php $component->render('cards/simple/end', [], true) ?>

<script type="text/javascript">
    loadComponents([
        "cardsSimple",
    ]);
</script>