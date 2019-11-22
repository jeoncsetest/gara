<aside class="sidebar sidebar-left sidebar-menu">
    <section class="content">
        <h5 class="heading">@lang("Organiser.organiser_menu")</h5>

        <ul id="nav" class="topmenu">
            <li class="{{ Request::is('*dashboard*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserDashboard', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-home2"></i></span>
                    <span class="text">@lang("Organiser.dashboard")</span>
                </a>
            </li>
            <li class="{{ Request::is('*events*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserEvents', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">@lang("Organiser.event")</span>
                </a>
            </li>
            <!--
            <li class="{{ Request::is('*coupons*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserCoupons', array('organiser_id' => $organiser->id,'organiserId' => $organiser->id, 'sort_by' =>''))}}">
                    <span class="figure"><i class="ico-gift"></i></span>
                    <span class="text">@lang("Organiser.coupons")</span>
                </a>
            </li>-->
            @if(!empty($organiser->id) && $organiser->account->id == 28)
                <li class="{{ Request::is('*schools*') ? 'active' : '' }}">
                    <a href="{{route('showAllSchools', array('organiser_id' => $organiser->id,'organiserId' => $organiser->id, 'sort_by' =>''))}}">
                        <span class="figure"><i class="ico-building"></i></span>
                        <span class="text">@lang("User.schools")</span>
                    </a>
                </li>

                <li class="{{ Request::is('*studnts*') ? 'active' : '' }}">
                    <a href="{{route('showAllStudents', array('organiser_id' => $organiser->id,'organiserId' => $organiser->id, 'sort_by' =>''))}}">
                        <span class="figure"><i class="ico-users"></i></span>
                        <span class="text">@lang("User.students")</span>
                    </a>
                </li>
            @endif

            <li class="{{ Request::is('*customize*') ? 'active' : '' }}">
                <a href="{{route('showOrganiserCustomize', array('organiser_id' => $organiser->id))}}">
                    <span class="figure"><i class="ico-cog"></i></span>
                    <span class="text">@lang("Organiser.customize")</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
