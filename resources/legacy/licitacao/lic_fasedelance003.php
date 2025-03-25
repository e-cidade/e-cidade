
<style>
    .d-none {
        display: none !important;
    }

    .skeleton-line {
        width: 100%;
        height: 15px;
        background: #e0e0e0;
        border-radius: 4px;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            background-color: #e0e0e0;
        }
        50% {
            background-color: #f0f0f0;
        }
        100% {
            background-color: #e0e0e0;
        }
    }

</style>

<div id="loading-screen" class="container">
    <div class="row">

        <div class="col-12">
            <div class="divider-row">
                <span class="divider-label">Dados do Processo</span>
            </div>
        </div>

        <div class="col-2">
            <div class="skeleton-line"></div>
        </div>

        <div class="col-4">
            <div class="skeleton-line"></div>
        </div>

        <div class="col-2">
            <div class="skeleton-line"></div>
        </div>

        <div class="col-2">
            <div class="skeleton-line"></div>
        </div>

        <div class="col-2">
            <div class="skeleton-line"></div>
        </div>

        <div class="col-12">
            <div class="skeleton-line"></div>
        </div>

        <div class="col-12">
            <div class="divider-row">
                <span class="divider-label">Itens do Processo</span>
            </div>
        </div>

    </div>

    <div class="skeleton-line"></div>


    <div class="group-of-buttons-bid-phase" style="margin-top: 20px;">
        <div class="skeleton-line"></div>

        <div class="skeleton-line"></div>

        <div class="skeleton-line"></div>

        <div class="skeleton-line"></div>
    </div>
</div>
