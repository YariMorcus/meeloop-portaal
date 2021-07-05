[33mcommit 285164f27e6907949412158f565c4c93cf2d915c[m[33m ([m[1;36mHEAD -> [m[1;32mmailinglist[m[33m, [m[1;31morigin/toevoegen-meeloopdag[m[33m, [m[1;32mmain[m[33m)[m
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Wed May 26 09:05:24 2021 +0200

    toevoegen meeloopdag user story

 admin/IVSMeeloopPortaal_AdminController.php |  4 [32m+[m[31m-[m
 admin/assets/ivs-styles.css                 | 90 [32m+++++++++++++++++++++++++++++[m
 admin/views/admin_toevoegen_meeloopdag.php  |  3 [31m-[m
 3 files changed, 92 insertions(+), 5 deletions(-)

[33mcommit 87d9d639e65d65d3688cc785db39f14c754aeaff[m[33m ([m[1;31morigin/create-menu-items[m[33m)[m
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Tue May 25 11:50:04 2021 +0200

    When plugin is activated, create admin menu items and pages

 admin/IVSMeeloopPortaal_AdminController.php | 122 [32m++++++++++++++++++++++++++++[m
 admin/assets/ivs-styles.css                 |  14 [32m++++[m
 admin/views/admin_main.php                  |   3 [32m+[m
 admin/views/admin_toevoegen_meeloopdag.php  |   3 [32m+[m
 includes/defs.php                           |   3 [32m+[m
 ivs-meeloop-portaal.php                     |  44 [32m+++++++++[m[31m-[m
 6 files changed, 187 insertions(+), 2 deletions(-)

[33mcommit f150879424ce62b5a8782e854b44a2348ea4650c[m
Merge: 524df64 9d53be9
Author: YariM <yarimorcus@zeelandnet.nl>
Date:   Tue May 25 09:50:12 2021 +0200

    Merge pull request #2 from YariMorcus/create-pages
    
    sprint 1 files

[33mcommit 9d53be9f4eae0998bbda802e14efabcc1e5fbd39[m[33m ([m[1;31morigin/create-pages[m[33m)[m
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Tue May 25 09:47:00 2021 +0200

    sprint 1 files

 includes/defs.php                                  |   5 [32m+[m
 includes/views/DatabaseInterface.php               | 153 [32m++++++++++++[m
 includes/views/PageView.php                        | 240 [32m+++++++++++++++++++[m
 includes/views/assets/fonts/FuturaPT-Bold.eot      | Bin [31m0[m -> [32m109036[m bytes
 includes/views/assets/fonts/FuturaPT-Bold.ttf      | Bin [31m0[m -> [32m108840[m bytes
 includes/views/assets/fonts/FuturaPT-Bold.woff     | Bin [31m0[m -> [32m48820[m bytes
 includes/views/assets/fonts/FuturaPT-Bold.woff2    | Bin [31m0[m -> [32m34264[m bytes
 includes/views/assets/fonts/FuturaPT-Book.eot      | Bin [31m0[m -> [32m102572[m bytes
 includes/views/assets/fonts/FuturaPT-Book.ttf      | Bin [31m0[m -> [32m102376[m bytes
 includes/views/assets/fonts/FuturaPT-Book.woff     | Bin [31m0[m -> [32m46508[m bytes
 includes/views/assets/fonts/FuturaPT-Book.woff2    | Bin [31m0[m -> [32m32844[m bytes
 includes/views/assets/fonts/FuturaPT-Heavy.eot     | Bin [31m0[m -> [32m105208[m bytes
 includes/views/assets/fonts/FuturaPT-Heavy.ttf     | Bin [31m0[m -> [32m105008[m bytes
 includes/views/assets/fonts/FuturaPT-Heavy.woff    | Bin [31m0[m -> [32m47548[m bytes
 includes/views/assets/fonts/FuturaPT-Heavy.woff2   | Bin [31m0[m -> [32m33472[m bytes
 includes/views/assets/fonts/demo.html              | 259 [32m+++++++++++++++++++++[m
 includes/views/assets/fonts/stylesheet.css         |  36 [32m+++[m
 .../fonts/transfonter.org-20210420-171522.zip      | Bin [31m0[m -> [32m554767[m bytes
 includes/views/assets/images/ivs-logo-black.png    | Bin [31m0[m -> [32m194698[m bytes
 includes/views/assets/style.css                    | 230 [32m++++++++++++++++++[m
 includes/views/not-logged-in-message.php           |  23 [32m++[m
 includes/views/template-dashboard.php              |  89 [32m+++++++[m
 includes/views/template-indienen-opdrachten.php    |  50 [32m++++[m
 includes/views/template-inzien-opdrachten.php      |  50 [32m++++[m
 includes/views/template-mijn-rooster.php           |  52 [32m+++++[m
 ivs-meeloop-portaal.php                            | 125 [32m+++++++++[m[31m-[m
 26 files changed, 1300 insertions(+), 12 deletions(-)

[33mcommit 524df64d0e5b7f88e8d4a849be8e917f5eb5a966[m
Merge: f225e7b 0aec24f
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Sun Apr 18 15:52:44 2021 +0200

    Merge branch 'main' of https://github.com/YariMorcus/meeloop-portaal

[33mcommit f225e7baf68a77ac3e386c3dae35197f5c6a59d1[m
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Sun Apr 18 15:47:30 2021 +0200

    Temporary disable activation and deactivation hooks

 ivs-meeloop-portaal.php | 11 [32m+++++++[m[31m----[m
 1 file changed, 7 insertions(+), 4 deletions(-)

[33mcommit 0aec24fca980326d5a7578f1dec80aae67551c69[m
Merge: 8463cb7 a4239e5
Author: YariM <yarimorcus@zeelandnet.nl>
Date:   Sun Apr 18 14:35:49 2021 +0200

    Merge pull request #1 from YariMorcus/master
    
    Initial commit

[33mcommit a4239e54590106e0d00c8ec52f8850afbb3bcd88[m[33m ([m[1;31morigin/master[m[33m)[m
Merge: 522d3e0 8463cb7
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Sun Apr 18 14:30:42 2021 +0200

    Merge branch 'main' of https://github.com/YariMorcus/meeloop-portaal

[33mcommit 522d3e0e37e30e070ea1d4d857c252a52fec58f7[m
Author: YariMorcus <yarimorcus@zeelandnet.nl>
Date:   Sun Apr 18 13:59:38 2021 +0200

    initial

 includes/defs.php       | 36 [32m++++++++++++++++++++++[m
 ivs-meeloop-portaal.php | 81 [32m+++++++++++++++++++++++++++++++++++++++++++++++++[m
 2 files changed, 117 insertions(+)

[33mcommit 8463cb7e434035b91c3e510c6beefb3ac5e800f0[m
Author: YariM <yarimorcus@zeelandnet.nl>
Date:   Sun Apr 18 13:50:07 2021 +0200

    Initial commit

 README.md | 1 [32m+[m
 1 file changed, 1 insertion(+)
