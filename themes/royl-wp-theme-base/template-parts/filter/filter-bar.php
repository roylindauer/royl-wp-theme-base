<div class="filter-bar">
    <form action="<?=home_url( add_query_arg( [] ) )?>" method="GET">
        <?php foreach ($fields as $field): ?>
        <?php $field->render(); ?>
        <?php endforeach; ?>
        <input type="submit" name="submit" value="Filter">
    </form>
</div>