import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { Category } from 'src/app/models/category';

import { take } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class CategoriesService {
  
  private _category: BehaviorSubject<Category[]> = new BehaviorSubject<Category[]>([]);

  constructor(private http: HttpClient) { }

  get categories(): Observable<Category[]> {
    return this._category.asObservable();
  }

  getCategories(parent: number){
    this._category.next([]);
    let options = {
      headers: new HttpHeaders({'Content-Type': 'application/json'})
    };
    this.http.get("http://localhost/DAW/M7_PHP/DAW2-Sintesi-Api-Salcedo/categories?parent=" + parent, options).subscribe(
      (response: any) => {
        response.forEach(
          (category:any) => {
            this.a(category);
        });
      }
    );
  }

  a(category:any){
    let categorys: Category = new Category();
    categorys.id = category.id;
    categorys.name = category.name;
    categorys.parentId = category.parent_id;
    
    this.categories.pipe(take(1)).subscribe(
      (originalCategory: Category[]) => {
        this._category.next(originalCategory.concat(categorys));
      }
    );
    
    if(category?.id != null) {
      this.http.get("http://localhost/DAW/M7_PHP/DAW2-Sintesi-Api-Salcedo/categories?parent=" + category.id).subscribe(
        (response:any) => {
          if (response.length > 0){
            response.forEach(
              (category:any) => {
                this.a(category);
            });
          }
        }
      );
    }
  }
}

