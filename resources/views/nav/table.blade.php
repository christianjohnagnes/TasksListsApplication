<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="all-tab" data-category="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="completed-tab" data-category="completed" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="true">Completed</a>
    </li>

     <li class="nav-item" role="presentation">
        <a class="nav-link" id="overdue-tab" data-category="overdue" data-toggle="tab" href="#overdue" role="tab" aria-controls="overdue" aria-selected="true">Overdue</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="low-tab" data-category="low" data-toggle="tab" href="#low" role="tab" aria-controls="low" aria-selected="false">Low</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="medium-tab" data-category="medium" data-toggle="tab" href="#medium" role="tab" aria-controls="medium" aria-selected="false">Medium</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="high-tab" data-category="high" data-toggle="tab" href="#high" role="tab" aria-controls="high" aria-selected="false">High</a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
        <table id="projectsTable" class="table table-striped table-dark"></table>
    </div>
</div>
