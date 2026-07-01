<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(1);
?>
<div class="flex items-center space-x-1 text-xs">
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getPreviousPage() ?>" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-700 transition-colors font-semibold" aria-label="<?= lang('Pager.previous') ?>">
            Sebelumnya
        </a>
    <?php else : ?>
        <span class="px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-400 cursor-not-allowed font-semibold">
            Sebelumnya
        </span>
    <?php endif ?>

    <div class="flex space-x-1">
        <?php foreach ($pager->links() as $link) : ?>
            <a href="<?= $link['uri'] ?>" class="px-3 py-1.5 rounded-lg transition-colors font-semibold <?= $link['active'] ? 'bg-brand-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' ?>">
                <?= $link['title'] ?>
            </a>
        <?php endforeach ?>
    </div>

    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNextPage() ?>" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-700 transition-colors font-semibold" aria-label="<?= lang('Pager.next') ?>">
            Selanjutnya
        </a>
    <?php else : ?>
        <span class="px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-400 cursor-not-allowed font-semibold">
            Selanjutnya
        </span>
    <?php endif ?>
</div>
