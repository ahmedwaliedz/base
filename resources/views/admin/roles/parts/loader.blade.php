<div class="table-loader w-100">
    <div class="row g-4 roles-skeleton-grid">
        @for ($i = 0; $i < 9; $i++)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="role-skeleton">
                    <div class="role-skeleton__header">
                        <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">
                            <div class="role-skeleton__icon flex-shrink-0"></div>
                            <div class="flex-grow-1 min-w-0 pt-1">
                                <div class="role-skeleton__line role-skeleton__line--title"></div>
                                <div class="role-skeleton__line role-skeleton__line--meta"></div>
                            </div>
                        </div>
                        <div class="role-skeleton__actions">
                            <div class="role-skeleton__dot"></div>
                            <div class="role-skeleton__dot"></div>
                            <div class="role-skeleton__dot"></div>
                        </div>
                    </div>
                    <div class="role-skeleton__divider"></div>
                    <div class="role-skeleton__footer">
                        <div class="role-skeleton__avatar"></div>
                        <div class="role-skeleton__avatar"></div>
                        <div class="role-skeleton__avatar"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
