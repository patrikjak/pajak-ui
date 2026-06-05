@use('Pajak\Ui\Common\Enums\SkeletonShape')

<tr class="pajak-table__row pajak-table__row--skeleton" aria-hidden="true">
    @foreach(range(1, $table->totalColumnCount()) as $i)
        <td class="pajak-table__td">
            <x-pajak::skeleton :shape="SkeletonShape::Line" style="width: {{ $loop->first ? '60%' : ($loop->last ? '40%' : '80%') }};" />
        </td>
    @endforeach
</tr>
