import './bootstrap';

const dirtyForms = Array.from(document.querySelectorAll('form[data-dirty-form]'));
const moneyInputs = Array.from(document.querySelectorAll('[data-money-input]'));
const copyButtons = Array.from(document.querySelectorAll('[data-copy-text]'));
const statusActionButtons = Array.from(document.querySelectorAll('[data-donation-status-action]'));
const statusModal = document.querySelector('[data-status-modal]');
const statusModalLabel = statusModal?.querySelector('[data-status-modal-label]');
const statusModalCancel = statusModal?.querySelector('[data-status-modal-cancel]');
const statusModalConfirm = statusModal?.querySelector('[data-status-modal-confirm]');
const sidebarGroupButtons = Array.from(document.querySelectorAll('[data-sidebar-group-toggle]'));
const adminSidebar = document.querySelector('[data-admin-sidebar]');
const adminSidebarBackdrop = document.querySelector('[data-admin-sidebar-backdrop]');
const adminSidebarOpenButton = document.querySelector('[data-admin-sidebar-open]');
const adminSidebarCloseButton = document.querySelector('[data-admin-sidebar-close]');
const adminSidebarLinks = Array.from(document.querySelectorAll('[data-admin-sidebar-link]'));

const formatMoneyInput = (value) => {
    const digits = String(value ?? '').replace(/[^\d]/g, '');

    if (!digits) {
        return '';
    }

    return new Intl.NumberFormat('id-ID').format(Number(digits));
};

moneyInputs.forEach((input) => {
    input.value = formatMoneyInput(input.value);

    input.addEventListener('input', () => {
        input.value = formatMoneyInput(input.value);
    });

    input.form?.addEventListener('submit', () => {
        input.value = String(input.value ?? '').replace(/[^\d]/g, '');
    });
});

const presetButtons = Array.from(document.querySelectorAll('[data-preset-amount]'));
presetButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
        const amount = btn.dataset.presetAmount;
        const form = btn.closest('form');
        const amountInput = form?.querySelector('input[name="amount"]') || document.querySelector('input[name="amount"]');
        if (amountInput && amount) {
            amountInput.value = formatMoneyInput(amount);
            amountInput.dispatchEvent(new Event('input', { bubbles: true }));
            amountInput.focus();
        }
    });
});

const prayerTextarea = document.querySelector('textarea[name="prayer_message"]');
const prayerCounter = document.getElementById('prayer-counter');
if (prayerTextarea && prayerCounter) {
    const updateCounter = () => {
        prayerCounter.textContent = `${prayerTextarea.value.length} / 1.000 karakter`;
    };
    prayerTextarea.addEventListener('input', updateCounter);
    updateCounter();
}

dirtyForms.forEach((form) => {
    let isDirty = false;

    const markDirty = () => {
        isDirty = true;
        form.dataset.dirty = 'true';
    };

    const clearDirty = () => {
        isDirty = false;
        form.dataset.dirty = 'false';
    };

    form.addEventListener('input', markDirty);
    form.addEventListener('change', markDirty);
    form.addEventListener('submit', clearDirty);

    window.addEventListener('beforeunload', (event) => {
        if (!isDirty) {
            return;
        }

        event.preventDefault();
        event.returnValue = '';
    });

    document.addEventListener('click', (event) => {
        const target = event.target.closest('a, button');

        if (!target || !isDirty) {
            return;
        }

        if (target.closest('form') === form && target.type === 'submit') {
            return;
        }

        if (target.tagName === 'A' && target.getAttribute('href') && !target.hasAttribute('data-bypass-dirty-check')) {
            const confirmed = window.confirm('Perubahan pada form ini belum disimpan. Tetap tinggalkan halaman?');

            if (!confirmed) {
                event.preventDefault();
            }
        }
    });
});

copyButtons.forEach((button) => {
    button.addEventListener('click', async () => {
        const text = button.dataset.copyText ?? '';

        if (!text) {
            return;
        }

        const originalText = button.textContent;

        try {
            await navigator.clipboard.writeText(text);
            button.textContent = 'Berhasil Disalin';
        } catch (error) {
            button.textContent = 'Gagal Menyalin';
        }

        window.setTimeout(() => {
            button.textContent = originalText;
        }, 1800);
    });
});

let activeStatusForm = null;
let activeStatusValue = '';

const closeStatusModal = () => {
    if (!statusModal) {
        return;
    }

    statusModal.classList.add('hidden');
    statusModal.classList.remove('flex');
    statusModal.setAttribute('aria-hidden', 'true');
    activeStatusForm = null;
    activeStatusValue = '';
};

statusActionButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const form = button.closest('form');
        const status = button.dataset.donationStatus ?? '';
        const label = button.dataset.donationStatusLabel ?? 'Lanjutkan';

        if (!form || !statusModal || !status) {
            return;
        }

        activeStatusForm = form;
        activeStatusValue = status;

        const titleMap = {
            paid: 'Yakin dana sudah masuk?',
            failed: 'Yakin transaksi ini gagal?',
            expired: 'Yakin transaksi ini expired?',
        };

        if (statusModalLabel) {
            statusModalLabel.textContent = titleMap[status] ?? label;
        }

        statusModal.classList.remove('hidden');
        statusModal.classList.add('flex');
        statusModal.setAttribute('aria-hidden', 'false');
    });
});

statusModalCancel?.addEventListener('click', closeStatusModal);

statusModal?.addEventListener('click', (event) => {
    if (event.target === statusModal) {
        closeStatusModal();
    }
});

statusModalConfirm?.addEventListener('click', () => {
    if (!activeStatusForm || !activeStatusValue) {
        closeStatusModal();
        return;
    }

    const statusInput = activeStatusForm.querySelector('input[name="status"]');

    if (statusInput) {
        statusInput.value = activeStatusValue;
    }

    activeStatusForm.requestSubmit();
    closeStatusModal();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && statusModal && !statusModal.classList.contains('hidden')) {
        closeStatusModal();
    }
});

const openAdminSidebar = () => {
    if (!adminSidebar || !adminSidebarBackdrop) {
        return;
    }

    adminSidebar.classList.remove('-translate-x-full');
    adminSidebar.classList.add('translate-x-0');
    adminSidebarBackdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
};

const closeAdminSidebar = () => {
    if (!adminSidebar || !adminSidebarBackdrop) {
        return;
    }

    adminSidebar.classList.add('-translate-x-full');
    adminSidebar.classList.remove('translate-x-0');
    adminSidebarBackdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

const resetAdminSidebarForDesktop = () => {
    if (!adminSidebar || !adminSidebarBackdrop) {
        return;
    }

    if (window.matchMedia('(min-width: 1024px)').matches) {
        adminSidebar.classList.remove('-translate-x-full');
        adminSidebar.classList.add('translate-x-0');
        adminSidebarBackdrop.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
};

adminSidebarOpenButton?.addEventListener('click', openAdminSidebar);
adminSidebarCloseButton?.addEventListener('click', closeAdminSidebar);
adminSidebarBackdrop?.addEventListener('click', closeAdminSidebar);
adminSidebarLinks.forEach((link) => {
    link.addEventListener('click', () => {
        if (!window.matchMedia('(max-width: 1023px)').matches) {
            return;
        }

        closeAdminSidebar();
    });
});

window.addEventListener('resize', resetAdminSidebarForDesktop);
resetAdminSidebarForDesktop();

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        if (statusModal && !statusModal.classList.contains('hidden')) {
            closeStatusModal();
        }

        if (adminSidebarBackdrop && !adminSidebarBackdrop.classList.contains('hidden')) {
            closeAdminSidebar();
        }
    }
});

const setSidebarGroupState = (button, isOpen) => {
    const section = button.closest('section');
    const panel = section?.querySelector('[data-sidebar-group-panel]');
    const chevron = button.querySelector('[data-sidebar-chevron]');

    button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

    if (panel) {
        panel.classList.toggle('hidden', !isOpen);
    }

    if (chevron) {
        chevron.classList.toggle('rotate-180', isOpen);
    }
};

sidebarGroupButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const section = button.closest('section');
        const panel = section?.querySelector('[data-sidebar-group-panel]');
        const isOpen = button.getAttribute('aria-expanded') === 'true';

        sidebarGroupButtons.forEach((otherButton) => {
            if (otherButton !== button) {
                setSidebarGroupState(otherButton, false);
            }
        });

        setSidebarGroupState(button, !isOpen);

        if (panel && !isOpen) {
            panel.scrollIntoView({ block: 'nearest' });
        }
    });
});
