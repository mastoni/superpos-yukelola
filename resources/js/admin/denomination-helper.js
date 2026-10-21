/**
 * Denomination helper (Slice C) — the cash-drawer counting grid used on
 * the shift open + close forms. The cashier enters how many of each note
 * and coin they're holding; the component sums it live and, on "Use this
 * total", writes the sum into the linked cash input (by name, within the
 * same form). The per-denomination count inputs carry their own `name`
 * (e.g. `opening_denominations[100]`) so they submit natively with the
 * form — no extra serialization needed.
 */
export function denominationHelper({ denominations = [], field = 'opening_denominations', targetName = null }) {
    return {
        expanded: false,
        denoms: denominations,
        counts: {},

        normalizeCount(val) {
            if (val === '' || val === null || val === undefined) return 0;
            const n = Number(val);
            if (!Number.isFinite(n) || n <= 0) return 0;
            return Math.floor(n);
        },

        get total() {
            let t = 0;
            for (const d of this.denoms) {
                t += this.normalizeCount(this.counts[d]) * Number(d);
            }
            return Number(t.toFixed(4));
        },

        subtotal(d) {
            return this.normalizeCount(this.counts[d]) * Number(d);
        },

        onInput(d, event) {
            let val = event.target.value;
            if (val === '' || val === null) {
                this.counts[d] = '';
            } else {
                let n = parseInt(val, 10);
                if (isNaN(n) || n < 0) {
                    n = 0;
                }
                this.counts[d] = n;
                event.target.value = (n === 0 && val === '') ? '' : n;
            }
            this.applyTotal();
        },

        applyTotal() {
            if (!targetName) return;
            const form = this.$root.closest('form');
            const el = form?.querySelector(`[name="${targetName}"]`);
            if (!el) return;
            el.value = this.total;
            // Nudge any x-model bound to the target so dependent computeds
            // (e.g. the close form's live variance) recompute.
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
        },
    };
}
