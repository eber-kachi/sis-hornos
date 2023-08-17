import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListAsignacionLoteComponent } from './list-asignacion-lote.component';

describe('ListAsignacionLoteComponent', () => {
  let component: ListAsignacionLoteComponent;
  let fixture: ComponentFixture<ListAsignacionLoteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ListAsignacionLoteComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ListAsignacionLoteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
