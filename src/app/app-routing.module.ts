import { NgModule } from '@angular/core';
import { Routes, RouterModule, PreloadAllModules } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { MainComponent } from './main/main.component';


const routes: Routes = [
  { 
    path: 'login', 
    component: LoginComponent
  },
  {
    path: 'main',
    component: MainComponent,
    children: [{
        path: '',
        loadChildren: () => import('./main/main.module').then(m => m.MainModule)
      }
    ]
  },
  { 
    path: '', 
    redirectTo: 'main', 
    pathMatch: 'full' 
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {preloadingStrategy: PreloadAllModules})],
  exports: [RouterModule]
})
export class AppRoutingModule { }
