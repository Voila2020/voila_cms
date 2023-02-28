@if (Request::input('fileformat') == 'pdf')
    <h3>{{ Request::input('filename') }}</h3>
@endif
<table border='1' width='100%' cellpadding='3' cellspacing="0" style='border-collapse: collapse;font-size:12px'>
    <thead>
        <tr>
            <?php

            foreach ($columns as $col) {
                echo "<th style='background:#eeeeee'>".$col->label_filed."</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        @if (count($applications) == 0)
            <tr class='warning'>
                <td colspan='{{ count($columns) + 1 }}' align="center">No Data Avaliable</td>
            </tr>
        @else
            @foreach ($applications as $row)
                <tr>
                    <?php
                    foreach ($columns as $col) {
                        $value = $row->fields[$col->id];

                        echo '<td>' . $value . '</td>';
                    }
                    ?>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
