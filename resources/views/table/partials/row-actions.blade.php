@use('Pajak\Ui\Table\Enums\ActionPosition')
@use('Pajak\Ui\Table\Actions\FormAction')
@use('Pajak\Ui\Table\Actions\ConfirmAction')
@use('Pajak\Ui\Table\Actions\ModalAction')

<td class="pajak-table__td pajak-table__td--actions">
    <div class="pajak-table-actions">
        @foreach($table->getActions() as $action)
            @if($action->isVisibleFor($row) && in_array(ActionPosition::Inline, $action->position()))
                @if($action instanceof ModalAction)
                    <button
                        type="button"
                        @class(['pajak-table-actions__btn', 'pajak-table-actions__btn--danger' => $action->isDanger()])
                        data-pajak-modal-open="{{ $action->resolveModalId($row) }}"
                    >
                        {{ $action->getLabel() }}
                    </button>
                @elseif($action instanceof ConfirmAction)
                    <button
                        type="button"
                        @class(['pajak-table-actions__btn', 'pajak-table-actions__btn--danger' => $action->isDanger()])
                        data-pajak-table-confirm="{{ $action->key() }}"
                        data-pajak-table-confirm-url="{{ $action->resolveUrl($row) }}"
                        data-pajak-table-confirm-method="{{ $action->httpMethod()->value }}"
                    >
                        {{ $action->getLabel() }}
                    </button>
                @elseif($action instanceof FormAction)
                    <button
                        type="button"
                        @class(['pajak-table-actions__btn', 'pajak-table-actions__btn--danger' => $action->isDanger()])
                        data-pajak-table-form-action="{{ $action->resolveUrl($row) }}"
                        data-pajak-table-form-method="{{ $action->httpMethod()->value }}"
                    >
                        {{ $action->getLabel() }}
                    </button>
                @else
                    <a
                        href="{{ $action->resolveUrl($row) }}"
                        @class(['pajak-table-actions__btn', 'pajak-table-actions__btn--danger' => $action->isDanger()])
                    >
                        {{ $action->getLabel() }}
                    </a>
                @endif
            @endif
        @endforeach

        @php($overflowActions = array_filter($table->getActions(), fn($a) => $a->isVisibleFor($row) && in_array(ActionPosition::Overflow, $a->position())))
        @if(count($overflowActions) > 0)
            <div class="pajak-table-actions__overflow" data-pajak-table-overflow>
                <button
                    type="button"
                    class="pajak-table-actions__overflow-btn"
                    data-pajak-table-overflow-trigger
                    aria-label="{{ __('pajak::table.actions.more') }}"
                >
                    <x-heroicon-s-ellipsis-horizontal width="16" height="16" aria-hidden="true" />
                </button>
                <div class="pajak-table-actions__overflow-menu" data-pajak-table-overflow-menu hidden>
                    @foreach($overflowActions as $action)
                        @if($action instanceof ConfirmAction)
                            <button
                                type="button"
                                @class(['pajak-table-actions__overflow-item', 'pajak-table-actions__overflow-item--danger' => $action->isDanger()])
                                data-pajak-table-confirm="{{ $action->key() }}"
                                data-pajak-table-confirm-url="{{ $action->resolveUrl($row) }}"
                                data-pajak-table-confirm-method="{{ $action->httpMethod()->value }}"
                            >
                                {{ $action->getLabel() }}
                            </button>
                        @elseif($action instanceof FormAction)
                            <button
                                type="button"
                                @class(['pajak-table-actions__overflow-item', 'pajak-table-actions__overflow-item--danger' => $action->isDanger()])
                                data-pajak-table-form-action="{{ $action->resolveUrl($row) }}"
                                data-pajak-table-form-method="{{ $action->httpMethod()->value }}"
                            >
                                {{ $action->getLabel() }}
                            </button>
                        @elseif($action instanceof ModalAction)
                            <button
                                type="button"
                                @class(['pajak-table-actions__overflow-item', 'pajak-table-actions__overflow-item--danger' => $action->isDanger()])
                                data-pajak-modal-open="{{ $action->resolveModalId($row) }}"
                            >
                                {{ $action->getLabel() }}
                            </button>
                        @else
                            <a
                                href="{{ $action->resolveUrl($row) }}"
                                @class(['pajak-table-actions__overflow-item', 'pajak-table-actions__overflow-item--danger' => $action->isDanger()])
                            >
                                {{ $action->getLabel() }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</td>
