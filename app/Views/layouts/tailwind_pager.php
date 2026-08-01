<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(1);
?>
<div class="flex items-center space-x-1.5 text-xs">
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getPreviousPage() ?>" class="px-4 py-2 bg-white border border-slate-200/80 rounded-full hover:bg-indigo-50/60 hover:border-indigo-200 hover:text-indigo-600 text-slate-600 shadow-xs transition-all duration-300 ease-in-out font-semibold flex items-center space-x-1.5" aria-label="<?= lang('Pager.previous') ?>">
            <i class="fas fa-chevron-left text-[10px]"></i>
            <span>Sebelumnya</span>
        </a>
    <?php else : ?>
        <span class="px-4 py-2 bg-slate-100/70 border border-slate-200/60 rounded-full text-slate-300 cursor-not-allowed font-semibold flex items-center space-x-1.5">
            <i class="fas fa-chevron-left text-[10px]"></i>
            <span>Sebelumnya</span>
        </span>
    <?php endif ?>

    <div class="flex items-center space-x-1 px-1">
        <?php foreach ($pager->links() as $link) : ?>
            <a href="<?= $link['uri'] ?>" class="w-8 h-8 flex items-center justify-center rounded-full transition-all duration-300 ease-in-out font-bold text-xs <?= $link['active'] ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25 scale-105' : 'bg-white border border-slate-200/80 text-slate-600 hover:bg-slate-100 hover:text-indigo-600' ?>">
                <?= $link['title'] ?>
            </a>
        <?php endforeach ?>
    </div>

    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNextPage() ?>" class="px-4 py-2 bg-white border border-slate-200/80 rounded-full hover:bg-indigo-50/60 hover:border-indigo-200 hover:text-indigo-600 text-slate-600 shadow-xs transition-all duration-300 ease-in-out font-semibold flex items-center space-x-1.5" aria-label="<?= lang('Pager.next') ?>">
            <span>Selanjutnya</span>
            <i class="fas fa-chevron-right text-[10px]"></i>
        </a>
    <?php else : ?>
        <span class="px-4 py-2 bg-slate-100/70 border border-slate-200/60 rounded-full text-slate-300 cursor-not-allowed font-semibold flex items-center space-x-1.5">
            <span>Selanjutnya</span>
            <i class="fas fa-chevron-right text-[10px]"></i>
        </span>
    <?php endif ?>
</div>

