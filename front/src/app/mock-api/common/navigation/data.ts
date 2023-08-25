/* tslint:disable:max-line-length */
import {FuseNavigationItem} from '@fuse/components/navigation';


export const defaultNavigation: FuseNavigationItem[] = [
 /* {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example',
    roles: ['administrador','ayudante', 'ayudante experto', 'jefe de contratos' ]
  }, */
  {
    id: 'lista-rol',
    title: 'Roles',
    type: 'basic',
    icon: 'heroicons_outline:clipboard-list',
    link: '/lista-rol',
    roles: ['administrador']
  },
  {
    id: 'lista-personal',
    title: 'Personal',
    type: 'basic',
    icon: 'heroicons_outline:users',
    link: '/lista-personal',
    roles: ['administrador', 'jefe de contratos']
  },
  {
    id: 'list-grupo-personal',
    title: 'Grupo Personal',
    type: 'basic',
    icon: 'heroicons_outline:user-group',
    link: '/lista-grupo',
    roles: ['administrador']
  },
  {
    id: 'lista-material',
    title: 'Materiales',
    type: 'basic',
    icon: 'heroicons_outline:view-grid-add',
    link: '/lista-material',
    roles: ['administrador']
  },
  {
    id: 'lista-pruducto',
    title: 'Productos',
    type: 'basic',
    icon: 'heroicons_outline:cube-transparent',
    link: '/lista-producto',
    roles: ['administrador', 'jefe de contratos']
  },
  {
    id: 'lista-departamento',
    title: 'Departamentos',
    type: 'basic',
    icon: 'heroicons_outline:filter',
    link: '/lista-departamento',
    roles: ['administrador']
  },  
  {
    id: 'lista-cliente',
    title: 'Clientes',
    type: 'basic',
    icon: 'heroicons_outline:identification',
    link: '/lista-cliente',
    roles: ['administrador']
  },
  {
    id: 'lista-pedidos',
    title: 'Pedidos',
    type: 'basic',
    icon: 'heroicons_outline:shopping-bag',
    link: '/lista-pedidos',
    roles: ['administrador']
  },
  {
    id: 'lista-lote',
    title: 'Lotes de Produccion',
    type: 'basic',
    icon: 'heroicons_outline:view-boards',
    link: '/lista-lote',
    roles: ['administrador', 'jefe de contratos']
  },
  {
    id: 'lista-proceso',
    title: 'Asignacion',
    type: 'basic',
    icon: 'heroicons_outline:view-boards',
    link: '/lista-asignacion',
    roles: ['administrador', 'jefe de contratos']
  },
  {
    id: 'lista-proceso',
    title: 'Procesos',
    type: 'basic',
    icon: 'heroicons_outline:view-boards',
    link: 'lista-proceso',
    roles: ['administrador', 'jefe de contratos']
  },
  {
    id: 'calendario',
    title: 'Cronograma',
    type: 'basic',
    icon: 'heroicons_outline:calendar',
    link: '/cronograma',
    roles: ['administrador','ayudante', 'ayudante experto', 'jefe de contratos' ]
  },
];





export const compactNavigation: FuseNavigationItem[] = [
  {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example'
  }
];
export const futuristicNavigation: FuseNavigationItem[] = [
  {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example'
  }
];
export const horizontalNavigation: FuseNavigationItem[] = [
  {
    id: 'example',
    title: 'Example',
    type: 'basic',
    icon: 'heroicons_outline:chart-pie',
    link: '/example',
  }
];


