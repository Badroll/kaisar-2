<!-- BEGIN: Theme CSS-->

<!-- Page Styles -->
@yield('page-style')

<style>
  .text-red {
    color: rgba(198, 44, 50, 1) !important;
  }

  .border-red {
    border-style: solid;
    border-color: rgba(198, 44, 50, 1) !important;
  }

  .border-blue {
    border-style: solid;
    border-color: #3F7CE8 !important;
  }

  .bg-glass {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(3px);
  }

  .bg-red {
    background-color: rgba(198, 44, 50, 1) !important;
    color: white;
  }

  .bg-red-100 {
    background-color: rgba(253, 243, 230, 1) !important;
    color: white;
  }

  .bg-blue-100 {
    background-color: rgba(229, 230, 253, 1) !important;
    color: white;
  }

  .text-blue {
    color: #3F7CE8;
  }

  .loading-spinner {
    width: 24px;
    height: 24px;
    border: 5px solid #FFF;
    border-bottom-color: #FF3D00;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 0.5s linear infinite;
  }

  @keyframes rotation {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>