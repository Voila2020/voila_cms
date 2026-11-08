<div id='header{{$index}}' data-collapsed="{{ (($form['collapsed'] ?? true) === false) ? 'false' : 'true' }}" class='header-title form-divider'>
    <h4>
        <strong><i class='{{ ($form['icon'] ?? null) ?: "fa fa-check-square-o" }}'></i> {{$form['label']}}</strong>
        <span class='pull-right icon'><i class='fa fa-minus-square-o'></i></span>
    </h4>
</div>