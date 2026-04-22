

<style>
    #sidebar {
        transition: transform 0.3s ease;
    }

    .sidebar-item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Hide scrollbar untuk sidebar nav */
    .sidebar-nav::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<aside id="sidebar"
    class="fixed lg:sticky top-0 left-0 z-40 w-64 h-screen flex-shrink-0 bg-white border-r border-gray-200 flex flex-col shadow-lg lg:shadow-sm transition-transform -translate-x-full lg:translate-x-0">

    
    <div class="sidebar-header h-16 flex items-center justify-between px-4 md:px-6 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-bold text-indigo-600 tracking-tight">YBS</span>
            <span class="text-base md:text-lg text-gray-500 font-medium">Management</span>
        </div>
        
        <button id="closeSidebarBtn" class="lg:hidden p-2 hover:bg-gray-200 rounded-lg transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    
    <nav class="sidebar-nav flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view dashboard')): ?>
            <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('dashboard')).'','active' => request()->routeIs('dashboard'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('dashboard')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('dashboard')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>']); ?>
                <span class="sidebar-item-text">Dashboard</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view oil losses', 'create oil losses', 'view olwb', 'view performance oil losses'])): ?>
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Oil Losses
            </p>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view oil losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('oil.index')).'','active' => request()->routeIs('oil.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('oil.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('oil.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>']); ?>
                    <span class="sidebar-item-text">Data Oil Losses</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create oil losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('oil.create')).'','active' => request()->routeIs('oil.create'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('oil.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('oil.create')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']); ?>
                    <span class="sidebar-item-text">Input Data Baru</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view olwb')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('oil.olwb')).'','active' => request()->routeIs('oil.olwb'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('oil.olwb')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('oil.olwb')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>']); ?>
                    <span class="sidebar-item-text">OLWB</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view performance oil losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('oil.report')).'','active' => request()->routeIs('oil.report'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('oil.report')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('oil.report')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Performance</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view kernel losses', 'create kernel losses', 'view rekap kernel losses', 'view performance kernel losses'])): ?>
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Kernel Losses
            </p>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.index')).'','active' => request()->routeIs('kernel.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>']); ?>
                    <span class="sidebar-item-text">Data Kernel Losses</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.create')).'','active' => request()->routeIs('kernel.create'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.create')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']); ?>
                    <span class="sidebar-item-text">Input Data Kernel Baru</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.dirt-moist.index')).'','active' => request()->routeIs('kernel.dirt-moist.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.dirt-moist.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.dirt-moist.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Data Dirt &amp; Moist</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.dirt-moist.create')).'','active' => request()->routeIs('kernel.dirt-moist.create'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.dirt-moist.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.dirt-moist.create')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']); ?>
                    <span class="sidebar-item-text">Input Data Dirt &amp; Moist</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.qwt.index')).'','active' => request()->routeIs('kernel.qwt.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M12 11v6m-3-3h6"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.qwt.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.qwt.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M12 11v6m-3-3h6"/></svg>']); ?>
                    <span class="sidebar-item-text">Data QWT Fibre Press</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.qwt.create')).'','active' => request()->routeIs('kernel.qwt.create'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.qwt.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.qwt.create')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']); ?>
                    <span class="sidebar-item-text">Input Data QWT Fibre Press</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.ripple-mill.index')).'','active' => request()->routeIs('kernel.ripple-mill.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.ripple-mill.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.ripple-mill.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>']); ?>
                    <span class="sidebar-item-text">Data Ripple Mill</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.ripple-mill.create')).'','active' => request()->routeIs('kernel.ripple-mill.create'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.ripple-mill.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.ripple-mill.create')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']); ?>
                    <span class="sidebar-item-text">Input Ripple Mill</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.destoner.index')).'','active' => request()->routeIs('kernel.destoner.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.destoner.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.destoner.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>']); ?>
                    <span class="sidebar-item-text">Data Destoner</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.destoner.create')).'','active' => request()->routeIs('kernel.destoner.create'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.destoner.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.destoner.create')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>']); ?>
                    <span class="sidebar-item-text">Input Destoner</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view rekap kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.rekap')).'','active' => request()->routeIs('kernel.rekap'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.rekap')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.rekap')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>']); ?>
                    <span class="sidebar-item-text">Rekap Data</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view performance kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('kernel.performance')).'','active' => request()->routeIs('kernel.performance'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('kernel.performance')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('kernel.performance')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Performance</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view laporan oil losses', 'view laporan kernel losses'])): ?>
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Laporan
            </p>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view laporan oil losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('reports.index')).'','active' => request()->routeIs('reports.index'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.index')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Laporan Oil Losses</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view laporan kernel losses')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('reports.kernel')).'','active' => request()->routeIs('reports.kernel'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.kernel')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.kernel')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Laporan Kernel Losses</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('reports.kernel.dirt-moist')).'','active' => request()->routeIs('reports.kernel.dirt-moist'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.kernel.dirt-moist')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.kernel.dirt-moist')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Laporan Dirt &amp; Moist</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('reports.kernel.qwt')).'','active' => request()->routeIs('reports.kernel.qwt'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.kernel.qwt')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.kernel.qwt')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Laporan QWT Fibre Press</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('reports.kernel.ripple-mill')).'','active' => request()->routeIs('reports.kernel.ripple-mill'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.kernel.ripple-mill')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.kernel.ripple-mill')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Laporan Ripple Mill</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('reports.kernel.destoner')).'','active' => request()->routeIs('reports.kernel.destoner'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('reports.kernel.destoner')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.kernel.destoner')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Laporan Destoner</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        
        <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
            Process
        </p>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view informasi proses mesin')): ?>
            <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('process.index')).'','active' => request()->routeIs('process.*'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('process.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('process.*')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                <span class="sidebar-item-text">Informasi Proses Mesin</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view performance sampel boy')): ?>
            <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('process.performance-sampel-boy')).'','active' => request()->routeIs('process.performance-sampel-boy'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('process.performance-sampel-boy')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('process.performance-sampel-boy')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/></svg>']); ?>
                <span class="sidebar-item-text">Performance Sampel Boy</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'view user activity log'])): ?>
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Administrasi
            </p>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view users')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('users.index')).'','active' => request()->routeIs('users.*'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('users.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('users.*')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>']); ?>
                    <span class="sidebar-item-text">Kelola Pengguna</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view user activity log')): ?>
                <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('activity-logs.index')).'','active' => request()->routeIs('activity-logs.*'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('activity-logs.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('activity-logs.*')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>']); ?>
                    <span class="sidebar-item-text">Activity Log</span>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Super Admin')): ?>
            <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e(route('permissions.index')).'','active' => request()->routeIs('permissions.*'),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('permissions.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('permissions.*')),'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>']); ?>
                <span class="sidebar-item-text">Permission Control</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>

    </nav>

    

    
    <div class="border-t border-gray-200 bg-gray-50">
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="w-full px-4 py-3 flex items-center gap-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors group"
                title="Logout">
                <div
                    class="w-9 h-9 rounded-full bg-red-100 group-hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-sm font-medium truncate">Logout</p>
                    <p class="text-xs text-gray-500 group-hover:text-red-500 truncate">
                        <?php echo e(Auth::user()->name); ?>

                        <?php if(Auth::user()->office): ?>
                            • <span class="font-semibold text-green-600"><?php echo e(Auth::user()->office); ?></span>
                        <?php endif; ?>
                    </p>
                </div>
            </button>
        </form>
    </div>

</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        // Close sidebar dari tombol X di mobile
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            });
        }
    });
</script><?php /**PATH D:\ybs\Project1YBS\resources\views/components/sidebar.blade.php ENDPATH**/ ?>