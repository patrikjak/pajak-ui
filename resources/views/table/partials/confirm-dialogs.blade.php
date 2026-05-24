@use('Pajak\Ui\Table\Actions\ConfirmAction')

@foreach($table->getActions() as $action)
    @if($action instanceof ConfirmAction)
        <dialog
            class="pajak-table-confirm"
            id="pajak-table-confirm-{{ $action->key() }}"
            data-pajak-table-confirm-dialog="{{ $action->key() }}"
        >
            <div class="pajak-table-confirm__inner">
                <h3 class="pajak-table-confirm__title">{{ $action->resolvedConfirmTitle() }}</h3>
                <p class="pajak-table-confirm__message">{{ $action->resolvedConfirmMessage() }}</p>
                <div class="pajak-table-confirm__footer">
                    <button
                        type="button"
                        class="pajak-table-confirm__cancel"
                        data-pajak-table-confirm-cancel
                    >
                        {{ __('pajak::table.confirm.cancel') }}
                    </button>
                    <button
                        type="button"
                        @class(['pajak-table-confirm__submit', 'pajak-table-confirm__submit--danger' => $action->isDanger()])
                        data-pajak-table-confirm-submit
                    >
                        {{ $action->resolvedConfirmButton() }}
                    </button>
                </div>
            </div>
        </dialog>
    @endif
@endforeach
