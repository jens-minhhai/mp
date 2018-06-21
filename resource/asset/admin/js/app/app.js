import './i18n';

$(() => {
    /**
     * Sidebar Dropdown
     */
    $('.nav-dropdown-toggle').on('click', (e) => {
        e.preventDefault();
        $(e.currentTarget).parent().toggleClass('open');
    });

    // open sub-menu when an item is active.
    $('ul.nav')
        .find('a.active')
        .parent()
        .parent()
        .parent()
        .addClass('open');

    /**
     * Sidebar Toggle
     */
    $('.sidebar-toggle').on('click', (e) => {
        e.preventDefault();
        $('body').toggleClass('sidebar-hidden');
    });

    /**
     * Mobile Sidebar Toggle
     */
    $('.sidebar-mobile-toggle').on('click', () => {
        $('body').toggleClass('sidebar-mobile-show');
    });
});

