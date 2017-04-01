<select class="<?=$field['classes']?>" name="<?=$field['name']?>" id="<?=$field['id']?>">
    <option value=""><?=\Royl\WpThemeBase\Util\Text::translate('- Select -');?></option>
    <?php foreach ($field['options'] as $k => $v): ?>
    <option value="<?=$k?>" <?=($field['value']==$k)?'selected="selected"':false?>><?=$v?></option>
    <?php endforeach; ?>
</select>