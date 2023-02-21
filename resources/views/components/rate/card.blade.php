<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Rates</h5> 
        <div class="card-tools">
            <a href="{{ $createFormRoute }}" class="bn btn-primary btn-sm ml-1"><i class="fas fa-plus mr-1"></i>Create</a>
        </div>   
    </div>
    <div class="card-body">
        <x-rate.table :$rates table-id="rates-table" />
    </div>
</div>