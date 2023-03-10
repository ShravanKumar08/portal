<?php $flag = isset($flag) ? $flag : true;?>
@foreach($data as $row)
    @if ($row == reset($data) && $flag)
        <tr>
            @foreach($row as $key => $value)
                <th>{!! $key !!}</th>
            @endforeach
            <?php $flag = false;?>
        </tr>
    @endif
    <tr>
        @foreach($row as $key => $value)
            @if(is_string($value) || is_numeric($value))
                <td>{!! $value !!}</td>
            @else
                <td></td>
            @endif
        @endforeach
    </tr>
    @if(isset($invoice_child_rows))
        <tr>
            <td colspan="{{ count($row) + 1 }}">
                {!! $invoice_child_rows[$row['Number']] !!}
            </td>
        </tr>
    @endif
    @if(isset($payout_child_rows))
        <tr>
            <td colspan="{{ count($row) + 1 }}">
                {!! $payout_child_rows[$row['ID']] !!}
            </td>
        </tr>
    @endif
@endforeach