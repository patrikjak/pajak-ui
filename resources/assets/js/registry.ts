export interface ComponentInstance {
    destroy(): void;
}

export function createRegistry<T extends ComponentInstance>() {
    const map = new WeakMap<HTMLElement, T>();

    return {
        has(el: HTMLElement): boolean {
            return map.has(el);
        },

        get(el: HTMLElement): T | undefined {
            return map.get(el);
        },

        set(el: HTMLElement, instance: T): void {
            map.set(el, instance);
        },

        delete(el: HTMLElement): void {
            map.delete(el);
        },
    };
}
