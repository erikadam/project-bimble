<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-yellow border border-transparent rounded-md font-semibold text-xs text-brand-dark uppercase tracking-widest hover:bg-brand-yellow-darker focus:outline-none focus:ring-2 focus:ring-brand-yellow focus:ring-offset-2 dark:focus:ring-offset-brand-dark-secondary transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
