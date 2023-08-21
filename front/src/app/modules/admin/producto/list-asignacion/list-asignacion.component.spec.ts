import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListAsignacionComponent } from './list-asignacion.component';

describe('ListAsignacionComponent', () => {
  let component: ListAsignacionComponent;
  let fixture: ComponentFixture<ListAsignacionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ListAsignacionComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ListAsignacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
