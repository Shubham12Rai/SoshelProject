<link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
<style>
    ul.sidebar-menu.tree {
        padding-top: 20px;
    }
</style>
<aside style="font-family: 'Lato',sans-serif;" class="main-sidebar">
    <section class="sidebar" style="background-color: rgba(239,239,239); ">
        <ul class="sidebar-menu" data-widget="tree">
            {{-- <li class="header" style="color:#fff;"> MAIN MENU <i class="fa fa-level-down"></i></li>   --}}
            <li>
                <a href="{{ route('home') }}" title="Dashboard" style="color:black; text-decoration: none;"><span>
                        <h4>Dashboard</h4>
                    </span></a>
            </li>

            <li>
                <a href="{{ route('user.management') }}" style="color:black; text-decoration: none;">
                    <span>
                        <h4>User Management</h4>
                    </span>
                </a>
            </li>

            <li class="#">
                <a href="{{ route('manage.payments') }}" title="Blog"
                    style="color:black;text-decoration: none; "><span>
                        <h4>Manage Payments</h4>
                    </span></a>
            </li>

            <li class="#">
                {{-- {{ route('event.management') }}  --}}
                <a href="" title="Blog"
                    style="color:black;text-decoration: none; "><span>
                        <h4>Event Management</h4>
                    </span></a>
            </li>

            <li class="#">
                <a href="{{ route('notification.management') }}" title="Blog"
                    style="color:black;text-decoration: none; "><span>
                        <h4>Notification Management</h4>
                    </span></a>
            </li>

            <li class="#">
                {{-- {{ route('static.content.management') }} --}}
                <a href="" title="Blog" style="color:black; text-decoration: none; "><span>
                        <h4>Static Content<br>Management</h4>
                    </span></a>
            </li>

            <li class="#">
                {{-- {{ route('review.feedback') }} --}}
                <a href="{{ route('review.feedback') }}" title="Blog"
                    style="color:black; text-decoration: none; "><span>
                        <h4>Review Feedback</h4>
                    </span></a>
            </li>
        </ul>
    </section>
</aside>
