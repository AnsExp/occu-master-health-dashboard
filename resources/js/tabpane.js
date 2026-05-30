document.addEventListener('DOMContentLoaded', () => {
    const tabGroups = document.querySelectorAll('[data-tab-group]');

    tabGroups.forEach((group) => {
        const buttons = group.querySelectorAll('[data-tab-button]');
        const panels = group.querySelectorAll('[data-tab-panel]');
        const activeTab = group.dataset.activeTab || buttons[0]?.dataset.tabButton;

        const setActiveTab = (tabKey) => {
            buttons.forEach((button) => {
                const isActive = button.dataset.tabButton === tabKey;
                button.classList.toggle('border-b-2', isActive);
                button.classList.toggle('border-b-gray-900', isActive);
                button.classList.toggle('text-gray-900', isActive);
                button.classList.toggle('text-gray-600', !isActive);
                button.classList.toggle('hover:text-gray-900', !isActive);
            });

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== tabKey);
            });
        };

        buttons.forEach((button) => {
            button.addEventListener('click', () => setActiveTab(button.dataset.tabButton));
        });

        if (activeTab) {
            setActiveTab(activeTab);
        }
    });
});

console.log('Tab pane script loaded');