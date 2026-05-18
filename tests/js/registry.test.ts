import { describe, it, expect } from 'vitest';
import { createRegistry } from '../../resources/assets/js/registry';

describe('createRegistry', () => {
    it('returns undefined for unknown element', () => {
        const registry = createRegistry<{ destroy(): void }>();
        const el = document.createElement('div');

        expect(registry.get(el)).toBeUndefined();
    });

    it('has returns false for unknown element', () => {
        const registry = createRegistry<{ destroy(): void }>();
        const el = document.createElement('div');

        expect(registry.has(el)).toBe(false);
    });

    it('stores and retrieves an instance', () => {
        const registry = createRegistry<{ destroy(): void }>();
        const el = document.createElement('div');
        const instance = { destroy: () => {} };

        registry.set(el, instance);

        expect(registry.get(el)).toBe(instance);
        expect(registry.has(el)).toBe(true);
    });

    it('deletes an instance', () => {
        const registry = createRegistry<{ destroy(): void }>();
        const el = document.createElement('div');
        const instance = { destroy: () => {} };

        registry.set(el, instance);
        registry.delete(el);

        expect(registry.get(el)).toBeUndefined();
        expect(registry.has(el)).toBe(false);
    });

    it('isolates instances between elements', () => {
        const registry = createRegistry<{ destroy(): void }>();
        const a = document.createElement('div');
        const b = document.createElement('div');
        const instanceA = { destroy: () => {} };

        registry.set(a, instanceA);

        expect(registry.get(b)).toBeUndefined();
    });
});
