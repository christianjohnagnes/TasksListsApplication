<ul class="nav nav-tabs nav-tabs-line font-weight-bold" style="letter-spacing: 1px;" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="all-tab" data-category="all" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">
            <span class="nav-icon"><i class="fa-solid fa-globe"></i></span>
            <span class="nav-text">All</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="completed-tab" data-category="completed" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="true">
            <span class="nav-icon"><i class="fa-regular fa-circle-check"></i></span>
            <span class="nav-text">Complete</span>
        </a>
    </li>

    <li class="nav-item" role="presentation">
        <a class="nav-link" id="incompleted-tab" data-category="incompleted" data-toggle="tab" href="#incompleted" role="tab" aria-controls="incompleted" aria-selected="true">
            <span class="nav-icon"><i class="fa-regular fa-circle-xmark"></i></span>
            <span class="nav-text">In Complete</span>
        </a>
    </li>

     <li class="nav-item" role="presentation">
        <a class="nav-link" id="overdue-tab" data-category="overdue" data-toggle="tab" href="#overdue" role="tab" aria-controls="overdue" aria-selected="true">
            <span class="nav-icon"><i class="fa-regular fa-calendar"></i></span>
            <span class="nav-text">Overdue</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="low-tab" data-category="low" data-toggle="tab" href="#low" role="tab" aria-controls="low" aria-selected="false">
            <span class="nav-icon"><i class="fa-solid fa-temperature-low"></i></span>
            <span class="nav-text">Low</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="medium-tab" data-category="medium" data-toggle="tab" href="#medium" role="tab" aria-controls="medium" aria-selected="false">
            <span class="nav-icon"><i class="fa-solid fa-temperature-half"></i></span>
            <span class="nav-text">Medium</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="high-tab" data-category="high" data-toggle="tab" href="#high" role="tab" aria-controls="high" aria-selected="false">
            <span class="nav-icon"><i class="fa-solid fa-temperature-high"></i></span>
            <span class="nav-text">High</span>
        </a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active table-responsive" id="all" role="tabpanel" aria-labelledby="all-tab">
        <table id="projectsTable" class="table dt-responsive table-striped table-hover display nowrap" cellspacing="0">
            <thead class="thead-dark"></thead>
        </table>
    </div>
</div>
