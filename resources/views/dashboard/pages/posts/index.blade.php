@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

 @php 
    function shortenBlogContent($content, $limit = 50) {
        $words = explode(" ", strip_tags($content)); // remove HTML first
        if (count($words) <= $limit) {
            return $content;
        }
        return implode(" ", array_slice($words, 0, $limit)) . '...';
    }
 @endphp

<main class="dashboard-main">
  <div class="navbar-header">
    <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" placeholder="Search">
          <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
        </form>
      </div>
    </div>
    @include('dashboard.subheader')
    </div>
  </div> 
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-semibold mb-0">All Posts</h6>

</div>

        
        <div class="row">
                <div class="col-md-12">
                        @if(session('error')) 
                            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        {!! session('error') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(session('success'))  
                            <div class="alert alert-success bg-success-100 text-success-600 border-success-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        {!! session('success') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>  
                        @endif
                </div>
                
        </div>

   
            <!--new row -->

            <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                          <div class=" align-items-center">
                                <div class="row">
                                      <div class="table-responsive">
                                    <table class="table colored-row-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="bg-base">Sn</th>
                                            <th scope="col" class="bg-base">Thumbnail</th>    
                                            <th scope="col" class="bg-base">Title</th>    
                                            <th scope="col" class="bg-base">Content</th>
                                            <th scope="col" class="bg-base">Date</th>
                                            <th scope="col" class="bg-base">Action</th>    
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @php
                                            // Define an array of background color classes
                                            $rowColors = ['bg-primary-light', 'bg-success-focus', 'bg-info-focus', 'bg-warning-focus', 'bg-danger-focus'];
                                        @endphp

                                        @foreach($posts as $key=>$post)
                                             @php
                                                // Pick a color based on row index, loop back using modulo
                                                $colorClass = $rowColors[$key % count($rowColors)];
                                             @endphp
                                             <tr>
                                                    <td class="{{ $colorClass }}">{{$key+1}}</td>
                                                    <td class="{{ $colorClass }}">
                                                    <div class="d-flex align-items-center">
                                                        <img src="uploads/{{$post->thumbnail}}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                                        
                                                    </div>
                                                    </td>    
                                                    <td class="{{ $colorClass }}">{{$post->title}}</td>
                                                    
                                                    <td class="{{ $colorClass }}">{{ shortenBlogContent($post->content, 3) }}</td>
                                                    <td class="{{ $colorClass }}"> {{date("M d, Y", strtotime($post->created_at))}}</td>
                                                    <td class="{{ $colorClass }}">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <a class="btn btn-info" href="{{route('posts.edit',$post->id)}}"><iconify-icon icon="tabler:edit" width="16" height="16"></iconify-icon></a>
                                                            
                                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <iconify-icon icon="ant-design:delete-outlined" width="16" height="16"></iconify-icon>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>    
                                                    
                                              </tr>
                                        @endforeach
                                        
                                        
                                        </tbody>
                                    </table>
                                    </div>   
                                </div>
                          </div>
                    </div>
                </div>
            </div>
               
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
<script>
    function confirmDelete(event) {
        const confirmed = confirm('Are you sure you want to delete this post?');
        if (!confirmed) {
            event.preventDefault(); // Stop form submission
            return false;
        }
        return true; // Allow form to submit
    }
</script>
@endsection




