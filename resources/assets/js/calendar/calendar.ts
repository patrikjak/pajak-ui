import { type ComponentInstance, createRegistry } from '../registry';

// ─── Types ────────────────────────────────────────────────────────────────────

interface DateState {
    year: number;
    month: number; // 0-based
    day: number;
}

interface RangeState {
    start: DateState | null;
    end: DateState | null;
    pending: DateState | null; // first click in range selection
    hovered: DateState | null; // for hover preview
}

const registry = createRegistry<ComponentInstance>();

// ─── Month / weekday names ────────────────────────────────────────────────────

const MONTHS = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December',
];

const WEEKDAYS_SHORT = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
const WEEKDAYS_FULL = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

// ─── Date helpers ─────────────────────────────────────────────────────────────

function today(): DateState {
    const d = new Date();
    return { year: d.getFullYear(), month: d.getMonth(), day: d.getDate() };
}

function datesEqual(a: DateState | null, b: DateState | null): boolean {
    if (!a || !b) {
        return false;
    }
    return a.year === b.year && a.month === b.month && a.day === b.day;
}

function dateLt(a: DateState, b: DateState): boolean {
    if (a.year !== b.year) {
        return a.year < b.year;
    }
    if (a.month !== b.month) {
        return a.month < b.month;
    }
    return a.day < b.day;
}

function dateLte(a: DateState, b: DateState): boolean {
    return datesEqual(a, b) || dateLt(a, b);
}

function parseIsoDate(iso: string): DateState | null {
    const m = iso.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (!m) {
        return null;
    }
    return { year: parseInt(m[1]!), month: parseInt(m[2]!) - 1, day: parseInt(m[3]!) };
}

function parseIsoDatetime(iso: string): { date: DateState; time: string } | null {
    const m = iso.match(/^(\d{4})-(\d{2})-(\d{2})(?:[T ](\d{2}:\d{2}))?/);
    if (!m) {
        return null;
    }
    return {
        date: { year: parseInt(m[1]!), month: parseInt(m[2]!) - 1, day: parseInt(m[3]!) },
        time: m[4] ?? '',
    };
}

function formatDate(d: DateState): string {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[d.month]} ${d.day}, ${d.year}`;
}

function toIso(d: DateState): string {
    return `${d.year}-${String(d.month + 1).padStart(2, '0')}-${String(d.day).padStart(2, '0')}`;
}

function toIsoWithTime(d: DateState, time: string): string {
    return `${toIso(d)} ${time}`;
}

// ─── Weekday index of a DateState (0=Mon … 6=Sun, matching WEEKDAYS_FULL) ────

function weekdayIndex(d: DateState): number {
    const jsDay = new Date(d.year, d.month, d.day).getDay(); // 0=Sun
    return jsDay === 0 ? 6 : jsDay - 1;
}

// ─── Panel positioning ────────────────────────────────────────────────────────

function positionPanel(trigger: HTMLElement, panel: HTMLElement): void {
    const rect = trigger.getBoundingClientRect();
    const gap = 4;
    const panelHeight = panel.offsetHeight;
    const spaceBelow = window.innerHeight - rect.bottom;
    const openUpward = spaceBelow < panelHeight + gap && rect.top > panelHeight + gap;

    panel.style.left = `${Math.round(rect.left)}px`;

    if (openUpward) {
        panel.style.top = `${Math.round(rect.top - panelHeight - gap)}px`;
    } else {
        panel.style.top = `${Math.round(rect.bottom + gap)}px`;
    }
}

// ─── Time input helpers ───────────────────────────────────────────────────────

function clampTime(raw: string): string {
    const cleaned = raw.replace(/[^\d:]/g, '');
    const parts = cleaned.split(':');
    const h = Math.min(23, parseInt(parts[0] ?? '0') || 0);
    const m = Math.min(59, parseInt(parts[1] ?? '0') || 0);
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
}

function wireTimeInput(input: HTMLInputElement, onChange: (time: string) => void): void {
    let lastValid = input.value || '';

    input.addEventListener('keydown', (e: KeyboardEvent) => {
        const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter'];
        if (allowed.includes(e.key)) {
            return;
        }
        if (!/^\d$/.test(e.key)) {
            e.preventDefault();
        }
    });

    input.addEventListener('input', () => {
        let val = input.value.replace(/[^\d]/g, '');
        if (val.length >= 3) {
            val = `${val.slice(0, 2)}:${val.slice(2, 4)}`;
        }
        input.value = val;
    });

    input.addEventListener('blur', () => {
        const val = input.value.trim();
        if (!val) {
            input.value = lastValid;
            return;
        }
        const clamped = clampTime(val.includes(':') ? val : `${val}:00`);
        input.value = clamped;
        lastValid = clamped;
        onChange(clamped);
    });
}

// ─── Grid renderer ────────────────────────────────────────────────────────────

function renderWeekdays(container: HTMLElement): void {
    container.innerHTML = '';
    WEEKDAYS_SHORT.forEach((wd, i) => {
        const el = document.createElement('div');
        el.className = 'pajak-datepicker__wd';
        el.setAttribute('role', 'columnheader');
        el.setAttribute('aria-label', WEEKDAYS_FULL[i]!);
        el.textContent = wd;
        container.appendChild(el);
    });
}

interface GridConfig {
    year: number;
    month: number;
    selected: DateState | null;
    rangeStart: DateState | null;
    rangeEnd: DateState | null;
    hovered: DateState | null;
    minDate: DateState | null;
    maxDate: DateState | null;
    onDayClick: (d: DateState) => void;
    onDayHover: (d: DateState | null) => void;
    onMonthNav: (delta: number) => void;
}

const RANGE_CLASSES = ['is-range-start', 'is-range-end', 'is-in-range'];

function applyRangeClasses(
    btn: HTMLElement,
    d: DateState,
    rangeStart: DateState | null,
    rangeEnd: DateState | null,
    hovered: DateState | null,
): void {
    btn.classList.remove(...RANGE_CLASSES);

    if (!rangeStart && !rangeEnd) {
        return;
    }

    const effectiveEnd = rangeEnd ?? hovered;

    let start = rangeStart;
    let end = effectiveEnd;
    if (start && end && dateLt(end, start)) {
        [start, end] = [end, start];
    }

    const isStart = datesEqual(d, start);
    const isEnd = datesEqual(d, end);

    if (isStart && isEnd) {
        btn.classList.add('is-range-start', 'is-range-end');
    } else if (isStart) {
        btn.classList.add('is-range-start');
    } else if (isEnd) {
        btn.classList.add('is-range-end');
    } else if (start && end && dateLt(start, d) && dateLt(d, end)) {
        btn.classList.add('is-in-range');
    }
}

function updateGridRangeClasses(
    grid: HTMLElement,
    rangeStart: DateState | null,
    rangeEnd: DateState | null,
    hovered: DateState | null,
): void {
    grid.querySelectorAll<HTMLElement>('.pajak-datepicker__day[data-date]').forEach((btn) => {
        const d = parseIsoDate(btn.dataset.date!);
        if (d) {
            applyRangeClasses(btn, d, rangeStart, rangeEnd, hovered);
        }
    });
}

// Returns the button that should receive tabindex="0" (selected > today > first non-disabled)
function resolveRovingTarget(
    grid: HTMLElement,
    selected: DateState | null,
    todayDate: DateState,
): HTMLElement | null {
    const all = Array.from(grid.querySelectorAll<HTMLButtonElement>('.pajak-datepicker__day:not(.is-muted)'));

    if (selected) {
        const sel = all.find((b) => b.dataset.date === toIso(selected));
        if (sel) {
            return sel;
        }
    }

    const tod = all.find((b) => b.dataset.date === toIso(todayDate));
    if (tod && !tod.disabled) {
        return tod;
    }

    return all.find((b) => !b.disabled) ?? null;
}

function applyRovingTabindex(grid: HTMLElement, target: HTMLElement | null): void {
    grid.querySelectorAll<HTMLElement>('.pajak-datepicker__day').forEach((btn) => {
        btn.setAttribute('tabindex', btn === target ? '0' : '-1');
    });
}

const gridKeydownControllers = new WeakMap<HTMLElement, AbortController>();

function renderGrid(grid: HTMLElement, cfg: GridConfig): void {
    const { year, month } = cfg;
    const todayDate = today();

    const firstDay = new Date(year, month, 1).getDay();
    const startOffset = firstDay === 0 ? 6 : firstDay - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    const totalCells = Math.ceil((startOffset + daysInMonth) / 7) * 7;

    grid.innerHTML = '';

    for (let i = 0; i < totalCells; i++) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'pajak-datepicker__day';
        btn.setAttribute('role', 'gridcell');
        btn.setAttribute('tabindex', '-1');

        let d: DateState;

        if (i < startOffset) {
            const day = daysInPrevMonth - startOffset + i + 1;
            d = { year: month === 0 ? year - 1 : year, month: month === 0 ? 11 : month - 1, day };
            btn.classList.add('is-muted');
        } else if (i >= startOffset + daysInMonth) {
            const day = i - startOffset - daysInMonth + 1;
            d = { year: month === 11 ? year + 1 : year, month: month === 11 ? 0 : month + 1, day };
            btn.classList.add('is-muted');
        } else {
            d = { year, month, day: i - startOffset + 1 };
        }

        btn.textContent = String(d.day);
        btn.dataset.date = toIso(d);

        // Full accessible label: "Wednesday, May 14, 2026"
        const wd = WEEKDAYS_FULL[weekdayIndex(d)]!;
        btn.setAttribute('aria-label', `${wd}, ${MONTHS[d.month]} ${d.day}, ${d.year}`);

        const isDisabled =
            (cfg.minDate !== null && dateLt(d, cfg.minDate)) ||
            (cfg.maxDate !== null && dateLt(cfg.maxDate, d));

        if (isDisabled) {
            btn.classList.add('is-disabled');
            btn.disabled = true;
        }

        if (datesEqual(d, todayDate)) {
            btn.classList.add('is-today');
        }

        const isSelected = datesEqual(d, cfg.selected);
        if (isSelected) {
            btn.classList.add('is-selected');
            btn.setAttribute('aria-pressed', 'true');
        } else {
            btn.setAttribute('aria-pressed', 'false');
        }

        applyRangeClasses(btn, d, cfg.rangeStart, cfg.rangeEnd, cfg.hovered);

        if (!isDisabled) {
            btn.addEventListener('click', () => cfg.onDayClick(d));
            btn.addEventListener('mouseenter', () => cfg.onDayHover(d));
            btn.addEventListener('mouseleave', () => cfg.onDayHover(null));
        }

        grid.appendChild(btn);
    }

    // Apply roving tabindex
    const rovingTarget = resolveRovingTarget(grid, cfg.selected, todayDate);
    applyRovingTabindex(grid, rovingTarget);

    // Replace the keydown listener so only one is ever attached to this grid element
    gridKeydownControllers.get(grid)?.abort();
    const keydownController = new AbortController();
    gridKeydownControllers.set(grid, keydownController);

    grid.addEventListener('keydown', (e: KeyboardEvent) => {
        const focused = grid.querySelector<HTMLElement>('.pajak-datepicker__day[tabindex="0"]');
        if (!focused) {
            return;
        }

        const all = Array.from(grid.querySelectorAll<HTMLElement>('.pajak-datepicker__day'));
        const idx = all.indexOf(focused);

        let next: HTMLElement | null = null;

        if (e.key === 'ArrowRight') {
            next = all.slice(idx + 1).find((b) => !(b as HTMLButtonElement).disabled) ?? null;
            if (!next) {
                e.preventDefault();
                cfg.onMonthNav(1);
                return;
            }
        } else if (e.key === 'ArrowLeft') {
            next = all.slice(0, idx).reverse().find((b) => !(b as HTMLButtonElement).disabled) ?? null;
            if (!next) {
                e.preventDefault();
                cfg.onMonthNav(-1);
                return;
            }
        } else if (e.key === 'ArrowDown') {
            next = all[idx + 7] && !(all[idx + 7] as HTMLButtonElement).disabled ? all[idx + 7]! : null;
        } else if (e.key === 'ArrowUp') {
            next = all[idx - 7] && !(all[idx - 7] as HTMLButtonElement).disabled ? all[idx - 7]! : null;
        } else if (e.key === 'Home') {
            next = all.find((b) => !b.classList.contains('is-muted') && !(b as HTMLButtonElement).disabled) ?? null;
        } else if (e.key === 'End') {
            next = [...all].reverse().find((b) => !b.classList.contains('is-muted') && !(b as HTMLButtonElement).disabled) ?? null;
        } else if (e.key === 'PageUp') {
            e.preventDefault();
            cfg.onMonthNav(-1);
            return;
        } else if (e.key === 'PageDown') {
            e.preventDefault();
            cfg.onMonthNav(1);
            return;
        }

        if (next) {
            e.preventDefault();
            applyRovingTabindex(grid, next);
            next.focus({ preventScroll: true });
        }
    }, { signal: keydownController.signal, capture: false });
}

// ─── Single date picker ───────────────────────────────────────────────────────

function upgradeSingle(wrap: HTMLElement): ComponentInstance {
    wrap.classList.add('is-upgraded');

    const trigger = wrap.querySelector<HTMLElement>('.pajak-datepicker__trigger')!;
    const display = wrap.querySelector<HTMLElement>('.pajak-datepicker__display')!;
    const panel = wrap.querySelector<HTMLElement>('.pajak-datepicker__panel')!;
    const titleEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__title')!;
    const weekdaysEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__weekdays')!;
    const gridEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__grid')!;
    const metaEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__meta')!;
    const announceEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__announce');
    const todayBtn = wrap.querySelector<HTMLElement>('.pajak-datepicker__action--today');
    const hiddenInput = wrap.querySelector<HTMLInputElement>('.pajak-datepicker__input')!;
    const timeInput = wrap.querySelector<HTMLInputElement>('.pajak-datepicker__time-input');

    const hasTime = wrap.hasAttribute('data-time');
    const minDate = wrap.dataset.min ? parseIsoDate(wrap.dataset.min) : null;
    const maxDate = wrap.dataset.max ? parseIsoDate(wrap.dataset.max) : null;

    let selectedDate: DateState | null = null;
    let selectedTime = '00:00';

    if (wrap.dataset.value) {
        const parsed = parseIsoDatetime(wrap.dataset.value);
        if (parsed) {
            selectedDate = parsed.date;
            if (parsed.time) {
                selectedTime = parsed.time;
            }
        }
    }

    const now = today();
    let viewYear = selectedDate?.year ?? now.year;
    let viewMonth = selectedDate?.month ?? now.month;

    const announce = (text: string): void => {
        if (!announceEl) {
            return;
        }
        announceEl.textContent = '';
        // Force DOM update so consecutive same-text announcements fire
        requestAnimationFrame(() => {
            announceEl.textContent = text;
        });
    };

    const updateDisplay = (): void => {
        if (!selectedDate) {
            display.textContent = wrap.dataset.placeholder ?? '';
            display.classList.add('is-placeholder');
            metaEl.textContent = '';
        } else {
            const formatted = hasTime
                ? `${formatDate(selectedDate)}, ${selectedTime}`
                : formatDate(selectedDate);
            display.textContent = formatted;
            display.classList.remove('is-placeholder');
            metaEl.textContent = formatted;
        }
    };

    const updateHiddenInput = (): void => {
        if (!selectedDate) {
            hiddenInput.value = '';
            return;
        }
        hiddenInput.value = hasTime
            ? toIsoWithTime(selectedDate, selectedTime)
            : toIso(selectedDate);
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const repaint = (): void => {
        const monthYear = `${MONTHS[viewMonth]} ${viewYear}`;
        titleEl.textContent = monthYear;
        renderGrid(gridEl, {
            year: viewYear,
            month: viewMonth,
            selected: selectedDate,
            rangeStart: null,
            rangeEnd: null,
            hovered: null,
            minDate,
            maxDate,
            onDayClick(d) {
                selectedDate = d;
                // Flush any typed-but-not-blurred time value before committing
                timeInput?.blur();
                updateDisplay();
                updateHiddenInput();
                if (timeInput) {
                    timeInput.value = selectedTime;
                }
                repaint();
                close();
            },
            onDayHover: () => {},
            onMonthNav(delta) {
                viewMonth += delta;
                if (viewMonth < 0) { viewMonth = 11; viewYear--; }
                else if (viewMonth > 11) { viewMonth = 0; viewYear++; }
                repaint();
                announce(`${MONTHS[viewMonth]} ${viewYear}`);
                // Restore focus to roving target after repaint
                const rovingBtn = gridEl.querySelector<HTMLElement>('.pajak-datepicker__day[tabindex="0"]');
                rovingBtn?.focus({ preventScroll: true });
            },
        });
    };

    const reposition = (): void => positionPanel(trigger, panel);

    const open = (): void => {
        renderWeekdays(weekdaysEl);
        repaint();
        document.body.appendChild(panel);
        panel.removeAttribute('hidden');
        positionPanel(trigger, panel);
        wrap.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        window.addEventListener('scroll', reposition, { passive: true, capture: true });
        window.addEventListener('resize', reposition, { passive: true });
        // Move focus into the grid
        const rovingBtn = gridEl.querySelector<HTMLElement>('.pajak-datepicker__day[tabindex="0"]');
        rovingBtn?.focus({ preventScroll: true });
    };

    const close = (): void => {
        panel.setAttribute('hidden', '');
        wrap.appendChild(panel);
        wrap.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
        window.removeEventListener('scroll', reposition, true);
        window.removeEventListener('resize', reposition);
        trigger.focus({ preventScroll: true });
    };

    const onDocMousedown = (e: MouseEvent): void => {
        const path = e.composedPath();
        if (!path.includes(wrap) && !path.includes(panel)) {
            close();
        }
    };

    trigger.addEventListener('click', () => {
        wrap.classList.contains('is-open') ? close() : open();
    });

    trigger.addEventListener('keydown', (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            close();
        }
        if ((e.key === 'Enter' || e.key === ' ') && !wrap.classList.contains('is-open')) {
            e.preventDefault();
            open();
        }
    });

    panel.addEventListener('keydown', (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            close();
        }
    });

    document.addEventListener('mousedown', onDocMousedown);

    wrap.querySelector<HTMLElement>('.pajak-datepicker__nav--prev')?.addEventListener('click', () => {
        if (viewMonth === 0) { viewMonth = 11; viewYear--; }
        else { viewMonth--; }
        repaint();
        announce(`${MONTHS[viewMonth]} ${viewYear}`);
    });

    wrap.querySelector<HTMLElement>('.pajak-datepicker__nav--next')?.addEventListener('click', () => {
        if (viewMonth === 11) { viewMonth = 0; viewYear++; }
        else { viewMonth++; }
        repaint();
        announce(`${MONTHS[viewMonth]} ${viewYear}`);
    });

    todayBtn?.addEventListener('click', () => {
        const t = today();
        selectedDate = t;
        viewYear = t.year;
        viewMonth = t.month;
        updateDisplay();
        updateHiddenInput();
        if (timeInput) {
            timeInput.value = selectedTime;
        }
        repaint();
        close();
    });

    if (timeInput) {
        timeInput.value = selectedTime;
        wireTimeInput(timeInput, (time) => {
            selectedTime = time;
            updateDisplay();
            updateHiddenInput();
        });
    }

    updateDisplay();

    return {
        destroy(): void {
            document.removeEventListener('mousedown', onDocMousedown);
            if (wrap.classList.contains('is-open')) {
                window.removeEventListener('scroll', reposition, true);
                window.removeEventListener('resize', reposition);
                wrap.appendChild(panel);
            }
            wrap.classList.remove('is-upgraded', 'is-open');
            registry.delete(wrap);
        },
    };
}

// ─── Range date picker ────────────────────────────────────────────────────────

function upgradeRange(wrap: HTMLElement): ComponentInstance {
    wrap.classList.add('is-upgraded');

    const trigger = wrap.querySelector<HTMLElement>('.pajak-datepicker__trigger')!;
    const display = wrap.querySelector<HTMLElement>('.pajak-datepicker__display')!;
    const panel = wrap.querySelector<HTMLElement>('.pajak-datepicker__panel')!;
    const titleEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__title')!;
    const weekdaysEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__weekdays')!;
    const gridEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__grid')!;
    const metaEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__meta')!;
    const announceEl = wrap.querySelector<HTMLElement>('.pajak-datepicker__announce');
    const applyBtn = wrap.querySelector<HTMLElement>('.pajak-datepicker__action--apply');
    const inputStart = wrap.querySelector<HTMLInputElement>('.pajak-datepicker__input-start')!;
    const inputEnd = wrap.querySelector<HTMLInputElement>('.pajak-datepicker__input-end')!;
    const timeInputStart = wrap.querySelector<HTMLInputElement>('.pajak-datepicker__time-input--start');
    const timeInputEnd = wrap.querySelector<HTMLInputElement>('.pajak-datepicker__time-input--end');

    const hasTime = wrap.hasAttribute('data-time');
    const minDate = wrap.dataset.min ? parseIsoDate(wrap.dataset.min) : null;
    const maxDate = wrap.dataset.max ? parseIsoDate(wrap.dataset.max) : null;

    const range: RangeState = { start: null, end: null, pending: null, hovered: null };
    let startTime = '00:00';
    let endTime = '23:59';

    if (wrap.dataset.start) {
        const parsed = parseIsoDatetime(wrap.dataset.start);
        if (parsed) {
            range.start = parsed.date;
            if (parsed.time) {
                startTime = parsed.time;
            }
        }
    }
    if (wrap.dataset.end) {
        const parsed = parseIsoDatetime(wrap.dataset.end);
        if (parsed) {
            range.end = parsed.date;
            if (parsed.time) {
                endTime = parsed.time;
            }
        }
    }

    const now = today();
    let viewYear = range.start?.year ?? now.year;
    let viewMonth = range.start?.month ?? now.month;

    const announce = (text: string): void => {
        if (!announceEl) {
            return;
        }
        announceEl.textContent = '';
        requestAnimationFrame(() => {
            announceEl.textContent = text;
        });
    };

    const formatRangeDate = (d: DateState, time: string): string => {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return hasTime ? `${months[d.month]} ${d.day}, ${time}` : `${months[d.month]} ${d.day}`;
    };

    const updateDisplay = (): void => {
        if (!range.start && !range.end) {
            display.textContent = wrap.dataset.placeholder ?? '';
            display.classList.add('is-placeholder');
            metaEl.textContent = '';
            return;
        }
        const s = range.start ? formatRangeDate(range.start, startTime) : '?';
        const e = range.end ? formatRangeDate(range.end, endTime) : '?';
        const label = range.end ? `${s} — ${e}, ${range.end.year}` : s;
        display.textContent = label;
        display.classList.remove('is-placeholder');
        metaEl.textContent = label;
    };

    const updateHiddenInputs = (): void => {
        inputStart.value = range.start
            ? (hasTime ? toIsoWithTime(range.start, startTime) : toIso(range.start))
            : '';
        inputEnd.value = range.end
            ? (hasTime ? toIsoWithTime(range.end, endTime) : toIso(range.end))
            : '';
        inputStart.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const repaint = (): void => {
        titleEl.textContent = `${MONTHS[viewMonth]} ${viewYear}`;

        const effectiveStart = range.pending ?? range.start;
        const effectiveEnd = range.pending ? null : range.end;

        renderGrid(gridEl, {
            year: viewYear,
            month: viewMonth,
            selected: null,
            rangeStart: effectiveStart,
            rangeEnd: effectiveEnd,
            hovered: null,
            minDate,
            maxDate,
            onDayClick(d) {
                if (!range.pending) {
                    range.pending = d;
                    range.end = null;
                } else {
                    let s = range.pending;
                    let e = d;
                    if (dateLt(e, s)) {
                        [s, e] = [e, s];
                    }
                    range.start = s;
                    range.end = e;
                    range.pending = null;
                    range.hovered = null;
                    if (hasTime) {
                        startTime = '00:00';
                        endTime = '23:59';
                        if (timeInputStart) {
                            timeInputStart.value = startTime;
                        }
                        if (timeInputEnd) {
                            timeInputEnd.value = endTime;
                        }
                    }
                }
                updateDisplay();
                repaint();
                panel.querySelectorAll<HTMLElement>('.pajak-datepicker__presets button.is-active, .pajak-datepicker__preset-btn.is-active')
                    .forEach((b) => b.classList.remove('is-active'));
            },
            onDayHover(d) {
                if (range.pending) {
                    range.hovered = d;
                    updateGridRangeClasses(gridEl, range.pending, null, d);
                }
            },
            onMonthNav(delta) {
                viewMonth += delta;
                if (viewMonth < 0) { viewMonth = 11; viewYear--; }
                else if (viewMonth > 11) { viewMonth = 0; viewYear++; }
                repaint();
                announce(`${MONTHS[viewMonth]} ${viewYear}`);
                const rovingBtn = gridEl.querySelector<HTMLElement>('.pajak-datepicker__day[tabindex="0"]');
                rovingBtn?.focus({ preventScroll: true });
            },
        });
    };

    const reposition = (): void => positionPanel(trigger, panel);

    const open = (): void => {
        renderWeekdays(weekdaysEl);
        const presetsEl = panel.querySelector<HTMLElement>('.pajak-datepicker__presets');
        if (presetsEl && !panel.classList.contains('has-presets')) {
            panel.classList.add('has-presets');
            wirePresets(presetsEl);
        }
        repaint();
        document.body.appendChild(panel);
        panel.removeAttribute('hidden');
        positionPanel(trigger, panel);
        wrap.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        window.addEventListener('scroll', reposition, { passive: true, capture: true });
        window.addEventListener('resize', reposition, { passive: true });
        const rovingBtn = gridEl.querySelector<HTMLElement>('.pajak-datepicker__day[tabindex="0"]');
        rovingBtn?.focus({ preventScroll: true });
    };

    const close = (): void => {
        panel.setAttribute('hidden', '');
        wrap.appendChild(panel);
        wrap.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
        window.removeEventListener('scroll', reposition, true);
        window.removeEventListener('resize', reposition);
        range.pending = null;
        range.hovered = null;
        trigger.focus({ preventScroll: true });
    };

    const wirePresets = (presetsEl: HTMLElement): void => {
        presetsEl.querySelectorAll<HTMLElement>('button[data-start][data-end]').forEach((btn) => {
            btn.classList.add('pajak-datepicker__preset-btn');
            btn.addEventListener('click', () => {
                const s = parseIsoDate(btn.dataset.start!);
                const e = parseIsoDate(btn.dataset.end!);
                if (!s || !e) {
                    return;
                }
                range.start = dateLte(s, e) ? s : e;
                range.end = dateLte(s, e) ? e : s;
                range.pending = null;
                range.hovered = null;
                if (hasTime) {
                    startTime = btn.dataset.startTime ?? '00:00';
                    endTime = btn.dataset.endTime ?? '23:59';
                    if (timeInputStart) {
                        timeInputStart.value = startTime;
                    }
                    if (timeInputEnd) {
                        timeInputEnd.value = endTime;
                    }
                }
                presetsEl.querySelectorAll('button').forEach((b) => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                if (range.end) {
                    viewYear = range.end.year;
                    viewMonth = range.end.month;
                }
                updateDisplay();
                repaint();
            });
        });
    };

    applyBtn?.addEventListener('click', () => {
        updateHiddenInputs();
        close();
    });

    const onDocMousedown = (e: MouseEvent): void => {
        const path = e.composedPath();
        if (!path.includes(wrap) && !path.includes(panel)) {
            close();
        }
    };

    trigger.addEventListener('click', () => {
        wrap.classList.contains('is-open') ? close() : open();
    });

    trigger.addEventListener('keydown', (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            close();
        }
        if ((e.key === 'Enter' || e.key === ' ') && !wrap.classList.contains('is-open')) {
            e.preventDefault();
            open();
        }
    });

    panel.addEventListener('keydown', (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            close();
        }
    });

    document.addEventListener('mousedown', onDocMousedown);

    wrap.querySelector<HTMLElement>('.pajak-datepicker__nav--prev')?.addEventListener('click', () => {
        if (viewMonth === 0) { viewMonth = 11; viewYear--; }
        else { viewMonth--; }
        repaint();
        announce(`${MONTHS[viewMonth]} ${viewYear}`);
    });

    wrap.querySelector<HTMLElement>('.pajak-datepicker__nav--next')?.addEventListener('click', () => {
        if (viewMonth === 11) { viewMonth = 0; viewYear++; }
        else { viewMonth++; }
        repaint();
        announce(`${MONTHS[viewMonth]} ${viewYear}`);
    });

    if (timeInputStart) {
        timeInputStart.value = startTime;
        wireTimeInput(timeInputStart, (time) => {
            startTime = time;
            updateDisplay();
        });
    }

    if (timeInputEnd) {
        timeInputEnd.value = endTime;
        wireTimeInput(timeInputEnd, (time) => {
            endTime = time;
            updateDisplay();
        });
    }

    updateDisplay();

    return {
        destroy(): void {
            document.removeEventListener('mousedown', onDocMousedown);
            if (wrap.classList.contains('is-open')) {
                window.removeEventListener('scroll', reposition, true);
                window.removeEventListener('resize', reposition);
                wrap.appendChild(panel);
            }
            wrap.classList.remove('is-upgraded', 'is-open');
            registry.delete(wrap);
        },
    };
}

// ─── Main upgrade dispatcher ──────────────────────────────────────────────────

function upgradeCalendar(wrap: HTMLElement): ComponentInstance {
    const existing = registry.get(wrap);
    if (existing) {
        return existing;
    }

    const instance = wrap.hasAttribute('data-range')
        ? upgradeRange(wrap)
        : upgradeSingle(wrap);

    registry.set(wrap, instance);
    return instance;
}

// ─── Public API ───────────────────────────────────────────────────────────────

export const PajakCalendar = {
    init(el: HTMLElement): ComponentInstance {
        return upgradeCalendar(el);
    },

    initAll(root: ParentNode = document): ComponentInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-datepicker]')).map(upgradeCalendar);
    },
};

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

window.Pajak = { ...window.Pajak, PajakCalendar };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => PajakCalendar.initAll());
} else {
    PajakCalendar.initAll();
}
